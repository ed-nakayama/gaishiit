<?php

namespace App\Services;

use Firebase\JWT\JWT;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use RuntimeException;

/**
 * Salesforce への認証・API 呼び出しをまとめたクライアント。
 *
 * 認証方式: JWT Bearer Flow
 * 必要な .env 設定:
 *   SF_CLIENT_ID=（Connected App の Consumer Key）
 *   SF_USERNAME=（インテグレーションユーザーのユーザー名）
 *   SF_LOGIN_URL=https://login.salesforce.com（または https://<mydomain>.my.salesforce.com）
 *   SF_PRIVATE_KEY_PATH=storage/app/salesforce/server.key
 *   SF_API_VERSION=v60.0
 */
class SalesforceClient
{
    private ?string $accessToken = null;
    private ?string $instanceUrl = null;

    public function __construct(
        private readonly string $clientId = '',
        private readonly string $username = '',
        private readonly string $loginUrl = '',
        private readonly string $privateKeyPath = '',
        private readonly string $apiVersion = 'v60.0',
    ) {
    }

    public static function fromConfig(): self
    {
        return new self(
            clientId: config('services.salesforce.client_id'),
            username: config('services.salesforce.username'),
            loginUrl: config('services.salesforce.login_url'),
            privateKeyPath: config('services.salesforce.private_key_path'),
            apiVersion: config('services.salesforce.api_version', 'v60.0'),
        );
    }

    /**
     * JWT Bearer Flow でアクセストークンを取得する。
     */
    public function authenticate(): void
    {
        $privateKey = file_get_contents(base_path($this->privateKeyPath));

        if ($privateKey === false) {
            throw new RuntimeException("秘密鍵が読み込めません: {$this->privateKeyPath}");
        }

        $now = time();
        $payload = [
            'iss' => $this->clientId,
            'sub' => $this->username,
            'aud' => $this->loginUrl,
            'exp' => $now + 180,
        ];

        $jwt = JWT::encode($payload, $privateKey, 'RS256');

        $response = Http::asForm()->post("{$this->loginUrl}/services/oauth2/token", [
            'grant_type' => 'urn:ietf:params:oauth:grant-type:jwt-bearer',
            'assertion' => $jwt,
        ]);

        if ($response->failed()) {
            throw new RuntimeException('Salesforce 認証に失敗しました: ' . $response->body());
        }

        $data = $response->json();
        $this->accessToken = $data['access_token'];
        $this->instanceUrl = $data['instance_url'];
    }

    /**
     * Bulk API 2.0 で CSV を Upsert する（ジョブ作成 → CSVアップロード → クローズ → 完了待ち → 結果取得）。
     *
     * @param string $sObjectType 例: 'Client_JD__c'
     * @param string $externalIdField 例: 'Xxx_Id__c' または 'Url_Key__c'
     * @param string $csvContent ヘッダー行を含むCSV文字列（列名はSalesforce側の項目API名と一致させること）
     * @return array{jobId: string, successCsv: string, failedCsv: string, state: string}
     */
    public function upsertViaBulkApi(string $sObjectType, string $externalIdField, string $csvContent): array
    {
        $jobId = $this->createIngestJob($sObjectType, 'upsert', $externalIdField);

        $this->uploadJobData($jobId, $csvContent);
        $this->closeJob($jobId);

        $finalState = $this->pollUntilComplete($jobId);

        return [
            'jobId' => $jobId,
            'state' => $finalState,
            'successCsv' => $this->getSuccessfulResults($jobId),
            'failedCsv' => $this->getFailedResults($jobId),
        ];
    }

    /**
     * Ingest ジョブを作成し、jobId を返す。
     */
    public function createIngestJob(string $sObjectType, string $operation, ?string $externalIdField = null): string
    {
        $this->ensureAuthenticated();

        $payload = [
            'object' => $sObjectType,
            'operation' => $operation, // 'insert' | 'update' | 'upsert' | 'delete'
            'contentType' => 'CSV',
        ];

        if ($operation === 'upsert') {
            $payload['externalIdFieldName'] = $externalIdField;
        }

        $response = $this->withAuthRetry(
            fn () => Http::withToken($this->accessToken)
                ->post("{$this->instanceUrl}/services/data/{$this->apiVersion}/jobs/ingest", $payload),
        );

        if ($response->failed()) {
            Log::error('Salesforce Bulk API ジョブ作成に失敗しました', [
                'sObjectType' => $sObjectType,
                'status' => $response->status(),
                'body' => $response->body(),
            ]);
            throw new RuntimeException("ジョブ作成失敗: {$response->status()} {$response->body()}");
        }

        return $response->json('id');
    }

    /**
     * ジョブに対して CSV データをアップロードする。
     * 改行コードはジョブ作成時のデフォルト設定(LF)に合わせて正規化する。
     */
    public function uploadJobData(string $jobId, string $csvContent): void
    {
        $this->ensureAuthenticated();

        // CRLF/CR混在のCSVをLFに統一する(Salesforceジョブのデフォルト改行設定と一致させるため)
        $csvContent = str_replace(["\r\n", "\r"], "\n", $csvContent);

        $response = $this->withAuthRetry(
            fn () => Http::withToken($this->accessToken)
                ->withBody($csvContent, 'text/csv')
                ->put("{$this->instanceUrl}/services/data/{$this->apiVersion}/jobs/ingest/{$jobId}/batches"),
        );

        if ($response->failed()) {
            Log::error('Salesforce Bulk API CSVアップロードに失敗しました', [
                'jobId' => $jobId,
                'status' => $response->status(),
                'body' => $response->body(),
            ]);
            throw new RuntimeException("CSVアップロード失敗: {$response->status()} {$response->body()}");
        }
    }

    /**
     * アップロード完了を通知し、Salesforce側の処理を開始させる。
     */
    public function closeJob(string $jobId): void
    {
        $this->ensureAuthenticated();

        $response = $this->withAuthRetry(
            fn () => Http::withToken($this->accessToken)
                ->patch(
                    "{$this->instanceUrl}/services/data/{$this->apiVersion}/jobs/ingest/{$jobId}",
                    ['state' => 'UploadComplete'],
                ),
        );

        if ($response->failed()) {
            Log::error('Salesforce Bulk API ジョブクローズに失敗しました', [
                'jobId' => $jobId,
                'status' => $response->status(),
                'body' => $response->body(),
            ]);
            throw new RuntimeException("ジョブクローズ失敗: {$response->status()} {$response->body()}");
        }
    }

    /**
     * ジョブが完了（JobComplete）または失敗（Failed/Aborted）するまでポーリングする。
     */
    public function pollUntilComplete(string $jobId, int $intervalSeconds = 5, int $timeoutSeconds = 300): string
    {
        $this->ensureAuthenticated();

        $elapsed = 0;

        while ($elapsed < $timeoutSeconds) {
            $response = $this->withAuthRetry(
                fn () => Http::withToken($this->accessToken)
                    ->get("{$this->instanceUrl}/services/data/{$this->apiVersion}/jobs/ingest/{$jobId}"),
            );

            if ($response->failed()) {
                throw new RuntimeException("ジョブ状態取得失敗: {$response->status()} {$response->body()}");
            }

            $state = $response->json('state');

            if (in_array($state, ['JobComplete', 'Failed', 'Aborted'], true)) {
                return $state;
            }

            sleep($intervalSeconds);
            $elapsed += $intervalSeconds;
        }

        throw new RuntimeException("ジョブ完了待ちがタイムアウトしました: jobId={$jobId}");
    }

    /**
     * SObjectの項目一覧(ラベル・API名など)を取得する。
     * CSVヘッダー(表示ラベル)からAPI参照名への変換に使う。
     *
     * @return array<int, array{name: string, label: string}>
     */
    public function describeFields(string $sObjectType): array
    {
        $this->ensureAuthenticated();

        $response = $this->withAuthRetry(
            fn () => Http::withToken($this->accessToken)
                ->get("{$this->instanceUrl}/services/data/{$this->apiVersion}/sobjects/{$sObjectType}/describe"),
        );

        if ($response->failed()) {
            throw new RuntimeException("Describe取得失敗: {$response->status()} {$response->body()}");
        }

        return array_map(
            fn (array $field) => ['name' => $field['name'], 'label' => $field['label']],
            $response->json('fields'),
        );
    }

    /**
     * ジョブの詳細情報(state、errorMessageなど)を取得する。
     * ジョブが Failed になった際の原因確認に使う。
     */
    public function getJobInfo(string $jobId): array
    {
        $this->ensureAuthenticated();

        $response = $this->withAuthRetry(
            fn () => Http::withToken($this->accessToken)
                ->get("{$this->instanceUrl}/services/data/{$this->apiVersion}/jobs/ingest/{$jobId}"),
        );

        if ($response->failed()) {
            throw new RuntimeException("ジョブ情報取得失敗: {$response->status()} {$response->body()}");
        }

        return $response->json();
    }

    /**
     * 成功レコードの結果をCSV文字列で取得する。
     */
    public function getSuccessfulResults(string $jobId): string
    {
        return $this->getJobResults($jobId, 'successfulResults');
    }

    /**
     * 失敗レコードの結果をCSV文字列で取得する（エラー内容の列を含む）。
     */
    public function getFailedResults(string $jobId): string
    {
        return $this->getJobResults($jobId, 'failedResults');
    }

    private function getJobResults(string $jobId, string $type): string
    {
        $this->ensureAuthenticated();

        $response = $this->withAuthRetry(
            fn () => Http::withToken($this->accessToken)
                ->get("{$this->instanceUrl}/services/data/{$this->apiVersion}/jobs/ingest/{$jobId}/{$type}"),
        );

        if ($response->failed()) {
            throw new RuntimeException("結果取得失敗（{$type}）: {$response->status()} {$response->body()}");
        }

        return $response->body();
    }

    /**
     * 削除対象のIdリストをBulk APIで一括削除する。
     *
     * @param array<int, string> $ids
     * @return array{jobId: string, state: string, successCsv: string, failedCsv: string}
     */
    public function deleteViaBulkApi(string $sObjectType, array $ids): array
    {
        $jobId = $this->createIngestJob($sObjectType, 'delete');

        $lines = ['Id'];
        foreach ($ids as $id) {
            $lines[] = $id;
        }

        $this->uploadJobData($jobId, implode("\n", $lines));
        $this->closeJob($jobId);

        $finalState = $this->pollUntilComplete($jobId);

        return [
            'jobId' => $jobId,
            'state' => $finalState,
            'successCsv' => $this->getSuccessfulResults($jobId),
            'failedCsv' => $this->getFailedResults($jobId),
        ];
    }

    /**
     * SOQLを実行し、件数上限なく全件を配列で取得する(Bulk API 2.0 Query)。
     * 匿名Apexの5,000行制限や、通常APIの件数上限を受けない。
     *
     * @return array<int, array<string, string>>
     */
    public function queryAll(string $soql): array
    {
        $jobId = $this->createQueryJob($soql);
        $this->pollQueryJobUntilComplete($jobId);

        $records = [];
        $locator = null;

        do {
            [$csv, $locator] = $this->fetchQueryResultsPage($jobId, $locator);
            $records = array_merge($records, $this->parseCsvToArray($csv));
        } while ($locator !== null);

        return $records;
    }

    private function createQueryJob(string $soql): string
    {
        $this->ensureAuthenticated();

        $response = $this->withAuthRetry(
            fn () => Http::withToken($this->accessToken)
                ->post("{$this->instanceUrl}/services/data/{$this->apiVersion}/jobs/query", [
                    'operation' => 'query',
                    'query' => $soql,
                    'contentType' => 'CSV',
                ]),
        );

        if ($response->failed()) {
            throw new RuntimeException("クエリジョブ作成失敗: {$response->status()} {$response->body()}");
        }

        return $response->json('id');
    }

    private function pollQueryJobUntilComplete(string $jobId, int $intervalSeconds = 5, int $timeoutSeconds = 300): string
    {
        $this->ensureAuthenticated();

        $elapsed = 0;

        while ($elapsed < $timeoutSeconds) {
            $response = $this->withAuthRetry(
                fn () => Http::withToken($this->accessToken)
                    ->get("{$this->instanceUrl}/services/data/{$this->apiVersion}/jobs/query/{$jobId}"),
            );

            if ($response->failed()) {
                throw new RuntimeException("クエリジョブ状態取得失敗: {$response->status()} {$response->body()}");
            }

            $state = $response->json('state');

            if (in_array($state, ['JobComplete', 'Failed', 'Aborted'], true)) {
                if ($state !== 'JobComplete') {
                    throw new RuntimeException("クエリジョブが失敗しました(state: {$state}): " . $response->body());
                }
                return $state;
            }

            sleep($intervalSeconds);
            $elapsed += $intervalSeconds;
        }

        throw new RuntimeException("クエリジョブ完了待ちがタイムアウトしました: jobId={$jobId}");
    }

    /**
     * @return array{0: string, 1: ?string} CSV文字列と、続きがある場合の次ページlocator(無ければnull)
     */
    private function fetchQueryResultsPage(string $jobId, ?string $locator): array
    {
        $this->ensureAuthenticated();

        $query = ['maxRecords' => 10000];
        if ($locator !== null) {
            $query['locator'] = $locator;
        }

        $response = $this->withAuthRetry(
            fn () => Http::withToken($this->accessToken)
                ->get("{$this->instanceUrl}/services/data/{$this->apiVersion}/jobs/query/{$jobId}/results", $query),
        );

        if ($response->failed()) {
            throw new RuntimeException("クエリ結果取得失敗: {$response->status()} {$response->body()}");
        }

        $nextLocator = $response->header('Sforce-Locator');
        $hasMore = $nextLocator && $nextLocator !== 'null';

        return [$response->body(), $hasMore ? $nextLocator : null];
    }

    /**
     * CSV文字列を連想配列の配列に変換する(1行目をヘッダーとして使用)。
     *
     * @return array<int, array<string, string>>
     */
    private function parseCsvToArray(string $csv): array
    {
        $csv = str_replace(["\r\n", "\r"], "\n", $csv);
        $lines = array_values(array_filter(explode("\n", $csv), fn ($l) => trim($l) !== ''));

        if (empty($lines)) {
            return [];
        }

        $header = str_getcsv(array_shift($lines));
        $rows = [];

        foreach ($lines as $line) {
            $row = str_getcsv($line);
            if (count($row) === count($header)) {
                $rows[] = array_combine($header, $row);
            }
        }

        return $rows;
    }

    private function ensureAuthenticated(): void
    {
        if ($this->accessToken === null) {
            $this->authenticate();
        }
    }

    /**
     * トークン失効（401）時に1回だけ再認証してリトライする共通処理。
     */
    private function withAuthRetry(callable $request)
    {
        $response = $request();

        if ($response->status() === 401) {
            $this->authenticate();
            $response = $request();
        }

        return $response;
    }
}
