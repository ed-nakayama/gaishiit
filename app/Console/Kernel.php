<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
//        GenerateSitemap::class
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
		// お気に入り登録データをメール送信
		$schedule->command('command:usermail')->everyFifteenMinutes(); // 15分毎

		// クチコミ申込みのお知らせをメール送信
		$schedule->command('command:evalmail')->everyFifteenMinutes(); // 15分毎

		// RPAからのexcel取込み
		$schedule->command('command:compjobsexcel')->everyFifteenMinutes()->unlessBetween('0:00', '6:50'); // 15分毎

		// 現在未使用
		$schedule->command('command:compjobscsv')->everyFifteenMinutes()->unlessBetween('3:00', '8:00'); // 15分毎

		// 6か月以上前のファイル削除
		$schedule->command('command:deloldfiles')->dailyAt('02:00');     // 毎日 2:00

		// 2週間以上前の更新日のjobを論理削除
		$schedule->command('command:compjobsexceldel')->dailyAt('03:00');     // 毎日 3:00

		// [Open]付きファイルの取込み
		$schedule->command('command:compjobsexcelupdate')->everyFifteenMinutes()->unlessBetween('3:00', '8:00'); // 15分毎

		// SFからのCSV取込み
		$schedule->command('command:sendopenupdate')->weeklyOn(1, '10:00'); // 毎週月曜日の10:00時に実行

		// SF用CSVファイル作成
		$schedule->command('command:dl_sfc')->dailyAt('07:00');     // 毎日 7:00

		// ランキング設定
		$schedule->command('command:setranking')->dailyAt('05:00');     // 毎日 5:00

		// 企業別ランキング　ジョブ
		$schedule->command('command:rankingjob')->dailyAt('05:30');     // 毎日 5:30

		// 新規ジョブ一覧
		$schedule->command('command:SendNewJob')->weeklyOn(1, '3:00');     // 月曜 3:00
		$schedule->command('command:SendNewJob')->weeklyOn(4, '3:00');     // 木曜 3:00

		// サイトマップ作成
		$schedule->command('sitemap:generate')->dailyAt('04:00');     // 毎日 4:00

    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
