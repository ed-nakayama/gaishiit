<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use App\Models\Job;

class GetJobVector extends Command
{
    // 属性(#[Signature]/#[Description])はLaravel 13以降でのみ有効なため、
    // サーバ側のLaravelバージョンに依存しない従来のプロパティ方式に変更。
    protected $signature = 'app:get-job-vector
        {--company_id= : テスト等で対象を絞りたい場合のみ指定}
        {--force : vectorが既に入っているレコードも再取得する}';

    protected $description = 'get job vector';

    // text-embedding-3-small は最大8191トークン。日本語の場合1文字≒1〜2トークン程度なので、
    // 安全マージンを見て6000文字までを入力上限とする（以前の100文字は短すぎて
    // intro冒頭の一文しかベクトルに反映されていなかったため修正）。
    private const MAX_INPUT_CHARS = 6000;

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $apiKey = config('const.OPENAI_API_KEY');

        // company_id を指定した場合のみ絞り込む。指定なしなら全社を対象にする。
        // デフォルトでは vector が未設定(NULL)のレコードのみを対象にする。
        // --force を付けた場合のみ、既にvectorがあるレコードも再取得する。
        $query = Job::selectRaw('id, intro, vector');
        if ($companyId = $this->option('company_id')) {
            $query->where('company_id', $companyId);
        }
        if (!$this->option('force')) {
            $query->whereNull('vector');
        }

        $mode = $this->option('force') ? '全件(force)' : '未取得分のみ';
        $this->info("Start : " . date("Y-m-d H:i:s") . " [対象: {$mode}]");

        $total = 0;
        $success = 0;

        try {
            // 全社対象だと件数が数千件規模になるため、chunkByIdでメモリに乗せすぎないようにする
            $query->orderBy('id')->chunkById(200, function ($jobs) use ($apiKey, &$total, &$success) {
                foreach ($jobs as $job) {
                    $total++;
                    $inputText = $job->intro;

                    if (empty($inputText)) {
                        $this->warn("ジョブID 【{$job->id}】: introが空のためスキップします。");
                        continue;
                    }

                    try {
                        $realApiUrl = 'https://api.openai.com/v1/embeddings';

                        $response = Http::withToken($apiKey)
                            ->withOptions([
                                'force_ip_resolve' => 'v4',
                            ])
                            ->timeout(30)
                            ->post($realApiUrl, [
                                'model' => 'text-embedding-3-small',
                                'input' => mb_substr($inputText, 0, self::MAX_INPUT_CHARS),
                            ]);

                        if ($response->successful()) {
                            $vector = $response->json()['data'][0]['embedding'] ?? null;

                            if ($vector === null) {
                                // 戻り値がnullの場合は更新せず、既存のvectorをそのまま残す
                                $this->warn("ジョブID 【{$job->id}】: embeddingを取得できなかったため更新をスキップします。");
                            } elseif (is_array($vector) && count($vector) === 1536) {
                                $job->vector = $vector;
                                $job->save();
                                $success++;
                                $this->info("ジョブID 【{$job->id}】 のベクトル化・DB保存に成功しました。");
                            } else {
                                // 1536次元でない不正なデータの場合も更新しない
                                $this->error("ジョブID 【{$job->id}】: AIから正しい1536次元の配列構造が戻りませんでした（更新スキップ）。");
                            }
                        } else {
                            // 課金不足・クォータ超過等のエラーは、以降のレコードを処理しても
                            // 同じエラーが続くだけなので、GetJobVectorBillingExceptionを投げて
                            // 処理全体を中断する（下のcatchブロックで拾う）。
                            if ($this->isBillingError($response->status(), $response->json())) {
                                throw new GetJobVectorBillingException(
                                    "課金/クォータ関連のエラーを検知したため処理を中断します（ジョブID: {$job->id}）: " . $response->body()
                                );
                            }

                            $this->error("API直接エラー（ジョブID: {$job->id}）: " . $response->body());
                        }
                    } catch (GetJobVectorBillingException $e) {
                        // 通常の例外とは扱いを分け、外側のtry/catchまで伝播させて処理を止める
                        throw $e;
                    } catch (\Exception $e) {
                        // タイムアウト等の一過性エラーはこのジョブだけスキップして継続
                        $this->error("例外エラー（ジョブID: {$job->id}）: " . $e->getMessage());
                    }

                    usleep(200000);
                }
            });
        } catch (GetJobVectorBillingException $e) {
            $this->error($e->getMessage());
            $this->error("課金/クォータエラーのため処理を中断しました。対象 {$total} 件中 {$success} 件成功した時点で終了。");
            return Command::FAILURE;
        }

        $this->info("End : " . date("Y-m-d H:i:s"));
        $this->info("本日のジョブベクトル化処理が完了しました。対象 {$total} 件中 {$success} 件成功。");
        return Command::SUCCESS;
    }

    /**
     * OpenAI APIのエラーレスポンスが、課金不足/クォータ超過等の
     * 「リトライしても解決しない」種類のエラーかどうかを判定する。
     */
    private function isBillingError(int $status, ?array $body): bool
    {
        $body = $body ?? [];
        $code = $body['error']['code'] ?? '';
        $type = $body['error']['type'] ?? '';
        $message = $body['error']['message'] ?? '';

        $haystack = mb_strtolower($code . ' ' . $type . ' ' . $message);

        // 429(レート制限)自体は一時的な場合もあるが、insufficient_quota等は
        // 課金設定を直さない限り解決しないため、これらのキーワードで判定する。
        $billingKeywords = [
            'insufficient_quota',
            'billing_hard_limit_reached',
            'exceeded your current quota',
            'quota',
            'billing',
        ];

        foreach ($billingKeywords as $keyword) {
            if (str_contains($haystack, $keyword)) {
                return true;
            }
        }

        return false;
    }
}

/**
 * 課金不足・クォータ超過など、リトライしても解決しないOpenAI APIエラーを表す例外。
 * 通常の例外(タイムアウト等)とは区別し、これが投げられた場合はコマンド全体を中断する。
 */
class GetJobVectorBillingException extends \RuntimeException
{
}
