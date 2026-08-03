<?php

namespace App\Console\Commands;

use App\Services\SalesforceClient;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

/**
 * storage/app/public/comp_jobs/sfc/ 配下のCSVファイルを、
 * ファイル名の昇順で1件ずつ Bulk API 2.0 経由で Salesforce へ Upsert するバッチコマンド。
 *
 * CSVのヘッダーはWeb版の手動インポートと同じ「表示ラベル」形式を前提とし、
 * Describe APIで取得した「表示ラベル -> API参照名」の対応を使って自動変換する。
 *
 * 外部IDキーは「外資Job番号」(API名: JobBango__c)。
 * この項目はLinux側で発行される番号が再利用されることがあるため、
 * Salesforce側では「外部ID」のみを設定し、「Unique」は付けない運用とする。
 *
 * 処理が終わったファイルは backup/ サブディレクトリへ移動する(成功・失敗にかかわらず)。
 * 全ファイル処理後、結果をまとめてメール通知する。
 *
 * 実行例:
 *   php artisan salesforce:sync
 *   php artisan salesforce:sync --file=/path/to/export.csv   （単一ファイルのみ指定したい場合）
 */
class SyncSalesforceData extends Command
{
    protected $signature = 'salesforce:sync {--file= : 単一ファイルのみ処理したい場合のCSVファイルパス（省略時はディレクトリ内の全CSVを昇順処理）}';

    protected $description = 'CSVディレクトリを昇順走査し、Bulk API 2.0 経由で Salesforceへ Upsertする（新規は挿入、既存は更新）';

    private const SOBJECT_TYPE = 'Client_JD__c';
    private const EXTERNAL_ID_FIELD = 'JobBango__c';

    /** CSV配置ディレクトリ（storage_path()からの相対パス） */
    private const CSV_DIR = 'app/public/comp_jobs/sfc';

    /** 処理済みファイルの移動先（CSV_DIR配下のサブディレクトリ名） */
    private const BACKUP_SUBDIR = 'backup';

    /**
     * CSVヘッダー(表示ラベル)からSalesforce項目API名を判定できない場合に、
     * 手動で対応を指定する上書き用マッピング。
     * 例: 同じ表示ラベルの項目が複数存在し、Describe結果だけでは一意に決まらない場合。
     *
     * 書式: 'CSVの表示ラベル' => 'API参照名'
     */
    private const LABEL_TO_API_NAME_OVERRIDE = [
        // '企業ID' => 'ClientID__c',
    ];

    /**
     * CSVには含まれるが、Salesforceへの送信対象外とする列(表示ラベル)。
     * 参考情報としてCSVに入っているだけで、実際の項目には対応しない列を指定する。
     */
    private const EXCLUDED_LABELS = [
        '企業名',
    ];

    public function handle(): int
    {
        $client = SalesforceClient::fromConfig();

        $singleFile = $this->option('file');
        $files = $singleFile ? [$singleFile] : $this->findCsvFiles();

        if (empty($files)) {
            $this->info('処理対象のCSVファイルが見つかりませんでした。');
            return self::SUCCESS;
        }

        $this->info(count($files) . ' 件のCSVファイルを処理します。');

        $results = [];

        foreach ($files as $filePath) {
            $this->info("=== {$filePath} を処理中 ===");

            $result = $this->processFile($client, $filePath);
            $results[] = $result;

            // 単一ファイル指定(--file)の場合は、テスト用途を想定してバックアップ移動しない
            if (! $singleFile) {
                $this->moveToBackup($filePath);
            }
        }

        $this->sendNotificationEmail($results);

        $hasFailure = collect($results)->contains(fn ($r) => ! $r['success']);

        return $hasFailure ? self::FAILURE : self::SUCCESS;
    }

    /**
     * CSV_DIR配下のCSVファイルを、ファイル名の昇順で取得する(backupサブディレクトリは除外)。
     *
     * @return array<int, string>
     */
    private function findCsvFiles(): array
    {
        $dir = storage_path(self::CSV_DIR);

        if (! is_dir($dir)) {
            $this->error("CSVディレクトリが見つかりません: {$dir}");
            return [];
        }

        $files = glob($dir . '/*.csv');

        if ($files === false) {
            return [];
        }

        sort($files, SORT_STRING); // ファイル名の昇順

        return $files;
    }

    /**
     * 1件のCSVファイルを読み込み、Bulk API 2.0でUpsertする。
     *
     * @return array{file: string, success: bool, successCount: int, failedCount: int, message: string}
     */
    private function processFile(SalesforceClient $client, string $filePath): array
    {
        $fileName = basename($filePath);

        if (! file_exists($filePath)) {
            $message = "ファイルが見つかりません: {$filePath}";
            $this->error($message);
            return ['file' => $fileName, 'success' => false, 'successCount' => 0, 'failedCount' => 0, 'message' => $message];
        }

        try {
            $records = $this->readCsv($filePath);
            $this->info(count($records) . ' 件のレコードを読み込みました。');

            if (empty($records)) {
                $message = 'レコードが0件のため、処理をスキップしました。';
                $this->warn($message);
                return ['file' => $fileName, 'success' => true, 'successCount' => 0, 'failedCount' => 0, 'message' => $message];
            }

            // 表示ラベル -> API参照名 のマッピングをDescribe APIから自動生成
            $labelToApiName = $this->buildLabelToApiNameMap($client);

            // レコードのキー(CSVヘッダー=表示ラベル)をAPI参照名に変換
            [$translatedRecords, $unmapped] = $this->translateHeaders($records, $labelToApiName);

            if (! empty($unmapped)) {
                $this->warn('以下の列は項目名に変換できなかったため、送信データから除外されました:');
                $this->warn(implode(', ', $unmapped));
                Log::warning('salesforce:sync 未マッピング列', ['file' => $fileName, 'columns' => $unmapped]);
            }

            if (! isset($translatedRecords[0][self::EXTERNAL_ID_FIELD])) {
                $message = self::EXTERNAL_ID_FIELD . ' 列がCSVから見つかりませんでした。';
                $this->error($message);
                return ['file' => $fileName, 'success' => false, 'successCount' => 0, 'failedCount' => 0, 'message' => $message];
            }

            $csv = $this->buildCsv($translatedRecords);

            $this->info('ジョブを実行します（' . count($translatedRecords) . ' 件）...');

            $result = $client->upsertViaBulkApi(self::SOBJECT_TYPE, self::EXTERNAL_ID_FIELD, $csv);

            $this->info("ジョブ状態: {$result['state']} (jobId: {$result['jobId']})");

            if ($result['state'] !== 'JobComplete') {
                $message = "ジョブが正常に完了しませんでした(state: {$result['state']}, jobId: {$result['jobId']})";
                Log::error('Salesforce Bulk API ジョブが正常終了しませんでした', ['file' => $fileName, 'jobId' => $result['jobId'], 'state' => $result['state']]);
                $this->error($message);
                return ['file' => $fileName, 'success' => false, 'successCount' => 0, 'failedCount' => count($translatedRecords), 'message' => $message];
            }

            $failedCount = max($this->countCsvRows($result['failedCsv']) - 1, 0); // ヘッダー行を除く
            $successCount = max($this->countCsvRows($result['successCsv']) - 1, 0);

            if ($failedCount > 0) {
                Log::warning('Salesforce Bulk API 失敗レコードあり', [
                    'file' => $fileName,
                    'jobId' => $result['jobId'],
                    'failedCsv' => $result['failedCsv'],
                ]);
            }

            $this->info("成功: {$successCount} 件 / 失敗: {$failedCount} 件");

            return [
                'file' => $fileName,
                'success' => $failedCount === 0,
                'successCount' => $successCount,
                'failedCount' => $failedCount,
                'message' => $failedCount > 0 ? "{$failedCount} 件のレコードが失敗しました。" : '正常に完了しました。',
            ];
        } catch (\Throwable $e) {
            $message = 'Salesforce連携でエラーが発生しました: ' . $e->getMessage();
            $this->error($message);
            Log::error('salesforce:sync 失敗', ['file' => $fileName, 'exception' => $e]);
            return ['file' => $fileName, 'success' => false, 'successCount' => 0, 'failedCount' => 0, 'message' => $message];
        }
    }

    /**
     * 処理が終わったファイルを backup サブディレクトリへ移動する。
     */
    private function moveToBackup(string $filePath): void
    {
        $backupDir = storage_path(self::CSV_DIR . '/' . self::BACKUP_SUBDIR);

        if (! is_dir($backupDir)) {
            mkdir($backupDir, 0755, true);
        }

        $destination = $backupDir . '/' . basename($filePath);

        // 同名ファイルが既にbackupにある場合はタイムスタンプを付けて重複を避ける
        if (file_exists($destination)) {
            $destination = $backupDir . '/' . date('Ymd_His') . '_' . basename($filePath);
        }

        if (! rename($filePath, $destination)) {
            $this->error("ファイルのバックアップ移動に失敗しました: {$filePath}");
            Log::error('salesforce:sync バックアップ移動失敗', ['file' => $filePath, 'destination' => $destination]);
        }
    }

    /**
     * 処理結果をまとめてメール通知する。
     *
     * @param array<int, array{file: string, success: bool, successCount: int, failedCount: int, message: string}> $results
     */
    private function sendNotificationEmail(array $results): void
    {
        $to = config('services.salesforce.notify_email');

        if (empty($to)) {
            $this->warn('通知先メールアドレス(SALESFORCE_SYNC_NOTIFY_EMAIL)が設定されていないため、メール送信をスキップしました。');
            return;
        }

        $hasFailure = collect($results)->contains(fn ($r) => ! $r['success']);
        $subject = ($hasFailure ? '[要確認] ' : '[完了] ') . 'Salesforce CSV同期処理結果';

        $lines = [];
        foreach ($results as $r) {
            $status = $r['success'] ? 'OK' : 'NG';
            $lines[] = "[{$status}] {$r['file']} : 成功 {$r['successCount']} 件 / 失敗 {$r['failedCount']} 件 / {$r['message']}";
        }

        $body = "Salesforce CSV同期処理が完了しました。\n\n" . implode("\n", $lines);

        try {
            Mail::raw($body, function ($message) use ($to, $subject) {
                $message->to($to)->subject($subject);
            });
        } catch (\Throwable $e) {
            $this->error('通知メールの送信に失敗しました: ' . $e->getMessage());
            Log::error('salesforce:sync 通知メール送信失敗', ['exception' => $e]);
        }
    }

    /**
     * Describe APIから「表示ラベル -> API参照名」のマッピングを作成する。
     * 手動指定(LABEL_TO_API_NAME_OVERRIDE)がある場合はそちらを優先する。
     *
     * @return array<string, string> ラベル => API参照名
     */
    private function buildLabelToApiNameMap(SalesforceClient $client): array
    {
        $fields = $client->describeFields(self::SOBJECT_TYPE);

        $map = [];
        foreach ($fields as $field) {
            // 同じラベルの項目が複数ある場合、最初に見つかったものを使う
            // (曖昧な場合は LABEL_TO_API_NAME_OVERRIDE で明示的に上書きすること)
            if (! isset($map[$field['label']])) {
                $map[$field['label']] = $field['name'];
            }
        }

        return array_merge($map, self::LABEL_TO_API_NAME_OVERRIDE);
    }

    /**
     * レコード配列のキー(CSVヘッダー=表示ラベル)を、API参照名に変換する。
     *
     * @param array<int, array<string, string>> $records
     * @param array<string, string> $labelToApiName
     * @return array{0: array<int, array<string, string>>, 1: array<int, string>} 変換後レコードと、変換できなかった列名一覧
     */
    private function translateHeaders(array $records, array $labelToApiName): array
    {
        if (empty($records)) {
            return [[], []];
        }

        $originalHeaders = array_keys($records[0]);
        $unmapped = [];
        $headerMap = [];

        foreach ($originalHeaders as $header) {
            if (in_array($header, self::EXCLUDED_LABELS, true)) {
                continue; // 参考用の列は意図的に除外(未マッピング警告にも含めない)
            }

            if (isset($labelToApiName[$header])) {
                $headerMap[$header] = $labelToApiName[$header];
            } else {
                $unmapped[] = $header;
            }
        }

        $translated = array_map(function (array $record) use ($headerMap) {
            $newRecord = [];
            foreach ($headerMap as $originalHeader => $apiName) {
                $newRecord[$apiName] = $record[$originalHeader] ?? '';
            }
            return $newRecord;
        }, $records);

        return [$translated, $unmapped];
    }

    /**
     * CSVを読み込み、連想配列の配列として返す（1行目をヘッダーとして使用）。
     *
     * @return array<int, array<string, string>>
     */
    private function readCsv(string $filePath): array
    {
        $rows = [];
        $handle = fopen($filePath, 'r');

        if ($handle === false) {
            throw new \RuntimeException("CSVを開けません: {$filePath}");
        }

        $header = fgetcsv($handle);

        while (($row = fgetcsv($handle)) !== false) {
            $rows[] = array_combine($header, $row);
        }

        fclose($handle);

        return $rows;
    }

    /**
     * 連想配列の配列を、ヘッダー行付きのCSV文字列に変換する。
     *
     * @param array<int, array<string, string>> $records
     */
    private function buildCsv(array $records): string
    {
        $handle = fopen('php://temp', 'r+');

        // レコード間で項目の出現順が揺れないよう、全レコードの列名の和集合をヘッダーにする
        $headers = [];
        foreach ($records as $record) {
            $headers = array_unique(array_merge($headers, array_keys($record)));
        }

        fputcsv($handle, $headers);

        foreach ($records as $record) {
            $row = array_map(fn ($h) => $record[$h] ?? '', $headers);
            fputcsv($handle, $row);
        }

        rewind($handle);
        $csv = stream_get_contents($handle);
        fclose($handle);

        return $csv;
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
