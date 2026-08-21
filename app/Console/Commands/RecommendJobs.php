<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\JobRecommendationService;

/**
 * Salesforceを介さずに、レコメンドロジック単体の動作確認を行うためのコマンド。
 *
 * 使い方:
 *   php artisan app:recommend-jobs --file=storage/app/sample_career.txt
 *   php artisan app:recommend-jobs --text="職務経歴の本文をそのまま指定"
 */
class RecommendJobs extends Command
{
    protected $signature = 'app:recommend-jobs
        {--file= : 職務経歴を書いたテキストファイルのパス}
        {--text= : 職務経歴を直接文字列で指定（--fileより優先）}
        {--count=5 : おすすめ件数（5/10/20のいずれか。デフォルト5）}';

    protected $description = '職務経歴テキストから、おすすめ募集要項をコンソールに表示する（Salesforce非経由の動作確認用）';

    public function handle(JobRecommendationService $service)
    {
        $careerText = $this->option('text');

        if (empty($careerText)) {
            $path = $this->option('file');
            if (empty($path)) {
                $this->error('--file か --text のどちらかを指定してください。');
                return Command::FAILURE;
            }
            if (!file_exists($path)) {
                $this->error("ファイルが見つかりません: {$path}");
                return Command::FAILURE;
            }
            $careerText = file_get_contents($path);
        }

        if (empty(trim($careerText))) {
            $this->error('職務経歴テキストが空です。');
            return Command::FAILURE;
        }

        $count = (int) $this->option('count');
        if (!in_array($count, JobRecommendationService::ALLOWED_COUNTS, true)) {
            $this->error(
                'おすすめ件数は ' . implode('/', JobRecommendationService::ALLOWED_COUNTS) . ' のいずれかを指定してください（指定値: ' . $count . '）。'
            );
            return Command::FAILURE;
        }

        $this->info('職務経歴を解析中...');
        $this->line('---');
        $this->line(mb_substr($careerText, 0, 200) . (mb_strlen($careerText) > 200 ? '...' : ''));
        $this->line('---');

        $start = microtime(true);

        try {
            $recommendations = $service->recommend($careerText, $count);
        } catch (\Exception $e) {
            $this->error('レコメンド処理に失敗しました: ' . $e->getMessage());
            return Command::FAILURE;
        }

        $elapsed = round(microtime(true) - $start, 2);

        if (empty($recommendations)) {
            $this->warn('おすすめできる募集要項が見つかりませんでした。');
            return Command::SUCCESS;
        }

        $this->info("完了（{$elapsed}秒）。おすすめ " . count($recommendations) . " 件:");
        $this->newLine();

        $rows = collect($recommendations)->map(function ($rec, $i) {
            return [
                $i + 1,
                $rec['job_id'],
                $rec['company_name'],
                $rec['job_title'],
                $rec['score'] ?? '-',
                mb_substr($rec['reason'], 0, 60),
            ];
        })->all();

        $this->table(
            ['#', 'Job ID', '企業名', 'ジョブタイトル', '類似度', 'おすすめ理由'],
            $rows
        );

        return Command::SUCCESS;
    }
}
