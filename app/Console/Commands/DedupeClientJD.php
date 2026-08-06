<?php

namespace App\Console\Commands;

use App\Services\SalesforceClient;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

/**
 * 指定した外部IDキー項目で重複しているレコードを検出し、
 * 更新日(LastModifiedDate)が最も新しいものだけを残して、
 * それ以外を削除するコマンド。
 *
 * Bulk API 2.0のクエリ/削除機能を使うため、匿名Apexの5,000行制限や
 * 本番環境でのApexクラス作成制限(ENTITY_IS_LOCKED)を受けない。
 *
 * 実行例:
 *   php artisan salesforce:dedupe --dry-run   （削除せず対象件数・一覧の確認のみ）
 *   php artisan salesforce:dedupe             （実際に削除を実行）
 */
class DedupeClientJD extends Command
{
    protected $signature = 'salesforce:dedupe
        {--object=Client_JD__c : 対象オブジェクトのAPI名}
        {--key-field=JobBango__c : 重複判定に使う外部IDキー項目のAPI名}
        {--dry-run : 削除を実行せず、対象件数と一覧の確認のみ行う}';

    protected $description = '外部IDキーの重複レコードを検出し、更新日が古い方を削除する（Bulk APIベース）';

    public function handle(): int
    {
        $client = SalesforceClient::fromConfig();

        $object = $this->option('object');
        $keyField = $this->option('key-field');
        $dryRun = (bool) $this->option('dry-run');

        $this->info("{$object} の {$keyField} を全件取得中...(件数によって数十秒〜数分かかります)");

        $records = $client->queryAll(
            "SELECT Id, {$keyField}, Client__c, LastModifiedDate FROM {$object} WHERE {$keyField} != null"
        );

        $this->info(count($records) . ' 件取得しました。重複を判定します...');

        [$duplicateKeyCount, $toDelete] = $this->findDuplicates($records, $keyField);

        $this->info("重複キー数: {$duplicateKeyCount} / 削除対象件数: " . count($toDelete));

        $reportPath = $this->saveReport($toDelete, $records, $keyField);
        $this->info("削除対象一覧を保存しました: {$reportPath}");

        if (empty($toDelete)) {
            $this->info('削除対象はありませんでした。');
            return self::SUCCESS;
        }

        if ($dryRun) {
            $this->warn('--dry-run のため、削除は実行していません。');
            $this->warn('保存された一覧を確認し、問題なければ --dry-run オプションを外して再実行してください。');
            return self::SUCCESS;
        }

        $this->info('削除を実行します...');

        $ids = array_map(fn ($r) => $r['Id'], $toDelete);
        $result = $client->deleteViaBulkApi($object, $ids);

        $this->info("ジョブ状態: {$result['state']} (jobId: {$result['jobId']})");

        if ($result['state'] !== 'JobComplete') {
            Log::error('salesforce:dedupe 削除ジョブが正常終了しませんでした', $result);
            $this->error('削除ジョブが正常に完了しませんでした。詳細はログを確認してください。');
            return self::FAILURE;
        }

        $successCount = max($this->countCsvRows($result['successCsv']) - 1, 0);
        $failedCount = max($this->countCsvRows($result['failedCsv']) - 1, 0);

        $this->info("削除成功: {$successCount} 件 / 削除失敗: {$failedCount} 件");

        if ($failedCount > 0) {
            Log::warning('salesforce:dedupe 削除失敗レコードあり', ['failedCsv' => $result['failedCsv']]);
            $this->warn('削除に失敗したレコードがあります。ログを確認してください。');
        }

        return self::SUCCESS;
    }

    /**
     * レコード一覧から、キー項目ごとに最新のもの以外を削除対象として判定する。
     *
     * @param array<int, array<string, string>> $records
     * @return array{0: int, 1: array<int, array<string, string>>}
     *         [0]=重複していたキーの種類数, [1]=削除対象レコードの配列
     */
    private function findDuplicates(array $records, string $keyField): array
    {
        $best = [];
        $toDelete = [];
        $duplicateKeys = [];

        foreach ($records as $r) {
            $key = $r[$keyField];
            $lastModified = $r['LastModifiedDate'];

            if (! isset($best[$key])) {
                $best[$key] = $r;
                continue;
            }

            $duplicateKeys[$key] = true;

            if ($lastModified > $best[$key]['LastModifiedDate']) {
                // 今回のレコードの方が新しいので、これまでのベストを削除対象に回す
                $toDelete[] = $best[$key];
                $best[$key] = $r;
            } else {
                $toDelete[] = $r;
            }
        }

        return [count($duplicateKeys), $toDelete];
    }

    /**
     * 削除対象・残すレコードの一覧を確認用CSVとして保存する。
     *
     * @param array<int, array<string, string>> $toDelete
     * @param array<int, array<string, string>> $allRecords
     */
    private function saveReport(array $toDelete, array $allRecords, string $keyField): string
    {
        $dir = storage_path('app/salesforce_dedupe');

        if (! is_dir($dir)) {
            mkdir($dir, 0755, true);
        }

        $path = $dir . '/dedupe_' . date('Ymd_His') . '.csv';

        $handle = fopen($path, 'w');
        fputcsv($handle, ['Id', $keyField, 'Client__c', 'LastModifiedDate', '判定']);

        $toDeleteIds = array_map(fn ($r) => $r['Id'], $toDelete);

        // 削除対象・削除対象のキーに対応する「残す」レコードの両方を分かりやすく出力する
        $keysInvolved = array_map(fn ($r) => $r[$keyField], $toDelete);

        foreach ($allRecords as $r) {
            if (! in_array($r[$keyField], $keysInvolved, true)) {
                continue;
            }

            $isDelete = in_array($r['Id'], $toDeleteIds, true);
            fputcsv($handle, [$r['Id'], $this->formatKeyValue($r[$keyField]), $r['Client__c'], $r['LastModifiedDate'], $isDelete ? 'DELETE' : 'KEEP']);
        }

        fclose($handle);

        return $path;
    }

    /**
     * Salesforceのクエリ結果でNumber型項目が "63377.0" のように返ってくる場合に、
     * 見やすさのため末尾の ".0" を除去する(整数値の場合のみ)。
     */
    private function formatKeyValue(string $value): string
    {
        if (preg_match('/^-?\d+\.0$/', $value)) {
            return substr($value, 0, -2);
        }

        return $value;
    }

    /**
     * CSV文字列の行数（ヘッダー含む）を数える。
     */
    private function countCsvRows(string $csv): int
    {
        if (trim($csv) === '') {
            return 0;
        }

        return count(array_filter(explode("\n", trim($csv))));
    }
}
