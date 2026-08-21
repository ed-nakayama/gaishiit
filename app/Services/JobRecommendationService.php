<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\Job;

/**
 * 職務経歴テキストから、おすすめ募集要項を選定するコアロジック。
 * Salesforce経由(JobRecommendController)でも、Salesforceを介さないローカル確認
 * (RecommendJobsコマンド)でも、同じロジックをここに集約して使う。
 */
class JobRecommendationService
{
    // ユーザーが選択できるおすすめ件数。デフォルトは先頭の5件。
    public const ALLOWED_COUNTS = [5, 10, 20];
    public const DEFAULT_COUNT = 5;

    // 1段目(ベクトル検索)で絞り込む候補プールの最低件数。
    // おすすめ件数が多い場合はGPTに選ぶ余地を持たせるため、件数に応じて広げる。
    private const CANDIDATE_POOL_MIN = 30;

    // 職務経歴テキストの入力上限（embeddingsの入力上限に合わせて安全マージンを確保）
    private const MAX_CAREER_TEXT_CHARS = 6000;

    public function recommend(string $careerText, int $count = self::DEFAULT_COUNT): array
    {
        if (!in_array($count, self::ALLOWED_COUNTS, true)) {
            throw new \InvalidArgumentException(
                'おすすめ件数は ' . implode('/', self::ALLOWED_COUNTS) . ' のいずれかを指定してください（指定値: ' . $count . '）。'
            );
        }

        $careerText = mb_substr($careerText, 0, self::MAX_CAREER_TEXT_CHARS);
        $apiKey = config('const.OPENAI_API_KEY');

        // --- 1. 候補者の職務経歴をベクトル化 ---
        $candidateVector = $this->embed($careerText, $apiKey);
        if ($candidateVector === null) {
            throw new \RuntimeException('職務経歴のベクトル化に失敗しました。');
        }

        // --- 2. 全募集要項とのコサイン類似度を計算し、上位N件に絞り込み ---
        // おすすめ件数(count)より十分広いプールをGPTに渡せるよう、件数に応じてプールサイズを調整する。
        $poolSize = max(self::CANDIDATE_POOL_MIN, $count * 3);

        $jobs = Job::whereNotNull('vector')
            ->select('id', 'company_id', 'name', 'intro', 'vector')
            ->get();

        if ($jobs->isEmpty()) {
            return [];
        }

        $scored = $jobs->map(function ($job) {
            // Jobモデルにarrayキャストが設定されていないため、DBに保存されているJSON文字列を
            // ここでデコードしてから使う(既にarrayの場合はそのまま通す)。
            $vector = is_array($job->vector) ? $job->vector : json_decode((string) $job->vector, true);
            return ['job' => $job, 'vector' => $vector];
        })->filter(function ($item) {
            return is_array($item['vector']) && count($item['vector']) === 1536;
        })->map(function ($item) use ($candidateVector) {
            return [
                'job' => $item['job'],
                'score' => $this->cosineSimilarity($candidateVector, $item['vector']),
            ];
        })->sortByDesc('score')->take($poolSize)->values();

        // --- 3. 絞り込んだ候補をGPTに渡し、最終的なおすすめ件数と理由付けを行わせる ---
        return $this->rerankWithGpt($careerText, $scored, $apiKey, $count);
    }

    private function embed(string $text, string $apiKey): ?array
    {
        try {
            $response = Http::withToken($apiKey)
                ->withOptions(['force_ip_resolve' => 'v4'])
                ->timeout(30)
                ->post('https://api.openai.com/v1/embeddings', [
                    'model' => 'text-embedding-3-small',
                    'input' => $text,
                ]);

            if ($response->successful()) {
                return $response->json()['data'][0]['embedding'] ?? null;
            }

            Log::error('embedding failed: ' . $response->body());
            return null;
        } catch (\Exception $e) {
            Log::error('embedding exception: ' . $e->getMessage());
            return null;
        }
    }

    private function cosineSimilarity(array $a, array $b): float
    {
        $dot = 0.0;
        $normA = 0.0;
        $normB = 0.0;
        $len = count($a);

        for ($i = 0; $i < $len; $i++) {
            $dot += $a[$i] * $b[$i];
            $normA += $a[$i] * $a[$i];
            $normB += $b[$i] * $b[$i];
        }

        if ($normA <= 0 || $normB <= 0) {
            return 0.0;
        }

        return $dot / (sqrt($normA) * sqrt($normB));
    }

    private function rerankWithGpt(string $careerText, $scoredCandidates, string $apiKey, int $count): array
    {
        $candidateLines = $scoredCandidates->map(function ($item) {
            $job = $item['job'];
            $companyName = $job->getCompanyName() ?? '(社名不明)';
            $title = $job->name ?? '(タイトル不明)';
            $intro = mb_substr((string) $job->intro, 0, 400);

            return "[{$job->id}] 企業名: {$companyName} / タイトル: {$title} / 概要: {$intro}";
        })->implode("\n");

        $systemPrompt = <<<EOT
あなたは人材紹介のキャリアアドバイザーです。
候補者の職務経歴と、与えられた募集要項のリストを比較し、
最もマッチ度が高いと考えられる募集要項を、リストの中から必ずちょうど{count}件選び、
それぞれについて一言でマッチ理由を日本語で説明してください。
候補が{count}件に満たない場合を除き、{count}件より少ない数を返してはいけません。

必ず以下のJSON形式のみで出力してください（前後に説明文をつけない）:
{"recommendations": [{"job_id": 123, "reason": "マッチ理由の説明"}, ...]}
EOT;
        $systemPrompt = str_replace('{count}', (string) $count, $systemPrompt);

        $userPrompt = "【候補者の職務経歴】\n{$careerText}\n\n【募集要項候補一覧】\n{$candidateLines}";

        try {
            $response = Http::withToken($apiKey)
                ->withOptions(['force_ip_resolve' => 'v4'])
                ->timeout(60)
                ->post('https://api.openai.com/v1/chat/completions', [
                    'model' => 'gpt-4o-mini',
                    'response_format' => ['type' => 'json_object'],
                    'messages' => [
                        ['role' => 'system', 'content' => $systemPrompt],
                        ['role' => 'user', 'content' => $userPrompt],
                    ],
                ]);

            if (!$response->successful()) {
                Log::error('rerank failed: ' . $response->body());
                return $this->fallbackToVectorTopN($scoredCandidates, $count);
            }

            $content = $response->json()['choices'][0]['message']['content'] ?? null;
            $parsed = json_decode($content, true);
            $picked = $parsed['recommendations'] ?? null;

            if (!is_array($picked)) {
                return $this->fallbackToVectorTopN($scoredCandidates, $count);
            }

            $jobById = $scoredCandidates->keyBy(fn ($item) => $item['job']->id);

            $result = [];
            $droppedCount = 0;
            foreach ($picked as $p) {
                $jobId = $p['job_id'] ?? null;
                $item = $jobById->get($jobId);
                if (!$item) {
                    // GPTが候補一覧に存在しないjob_idを返した場合はスキップする。
                    // 頻発するようならプロンプトやモデルの見直しが必要。
                    $droppedCount++;
                    continue;
                }
                $job = $item['job'];
                $result[] = [
                    'job_id' => $job->id,
                    'company_name' => $job->getCompanyName() ?? '(社名不明)',
                    'job_title' => $job->name ?? '(タイトル不明)',
                    'reason' => $p['reason'] ?? '',
                    'score' => round($item['score'], 4),
                ];
            }

            if ($droppedCount > 0) {
                Log::warning("GPTが候補一覧に無いjob_idを{$droppedCount}件返したため除外しました。");
            }

            // GPTの返却件数が要求より少ない場合（無効なjob_idの除外や、GPT自身が
            // {count}件に満たない数しか返さなかった場合）は、類似度上位から不足分を補う。
            if (count($result) < $count) {
                $shortfall = $count - count($result);
                $result = $this->fillShortfall($result, $scoredCandidates, $shortfall);
                Log::warning("GPTの選定結果が要求件数({$count})に満たなかったため、類似度順に{$shortfall}件を補完しました。");
            }

            // GPTが返す配列の順序はマッチ度順であることが保証されていないため、
            // 各候補が持つベクトル類似度スコア(score)を使って、必ずおすすめ度(スコア)の高い順に並べ替える。
            usort($result, fn ($a, $b) => $b['score'] <=> $a['score']);

            return array_slice($result, 0, $count);
        } catch (\Exception $e) {
            Log::error('rerank exception: ' . $e->getMessage());
            return $this->fallbackToVectorTopN($scoredCandidates, $count);
        }
    }

    // GPTの選定結果が要求件数に満たない場合、まだ選ばれていない候補の中から
    // 類似度が高い順に不足分を補って埋める。
    private function fillShortfall(array $result, $scoredCandidates, int $shortfall): array
    {
        $alreadyPicked = collect($result)->pluck('job_id')->all();

        $filled = $scoredCandidates
            ->reject(fn ($item) => in_array($item['job']->id, $alreadyPicked, true))
            ->take($shortfall)
            ->map(function ($item) {
                $job = $item['job'];
                return [
                    'job_id' => $job->id,
                    'company_name' => $job->getCompanyName() ?? '(社名不明)',
                    'job_title' => $job->name ?? '(タイトル不明)',
                    'reason' => '(AIの選定件数が不足していたため、類似度順に自動補完)',
                    'score' => round($item['score'], 4),
                ];
            })->values()->all();

        return array_merge($result, $filled);
    }

    private function fallbackToVectorTopN($scoredCandidates, int $count): array
    {
        return $scoredCandidates->take($count)->map(function ($item) {
            $job = $item['job'];
            return [
                'job_id' => $job->id,
                'company_name' => $job->getCompanyName() ?? '(社名不明)',
                'job_title' => $job->name ?? '(タイトル不明)',
                'reason' => '(AIによる理由付けに失敗したため、類似度順に表示しています)',
                'score' => round($item['score'], 4),
            ];
        })->values()->all();
    }
}
