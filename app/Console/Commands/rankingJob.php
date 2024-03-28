<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Models\Job;
use App\Models\Company;
use App\Models\RankingJob as RankingJobs;

class RankingJob extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:rankingjob';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'get ranking job';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
		$compList = Company::where('companies.open_flag', 1)
			->get();

		RankingJobs::truncate();

		foreach ($compList as $comp) {

			$jobList = Job::where('company_id', $comp->id)
				->where('open_flag', 1)
				->whereNotNull('intro')
				->where('intro','!=', '')
				->orderBy('updated_at')
				->take(3)
				->get();

			if (!empty($jobList[0])) {
				foreach ($jobList as $job) {
					RankingJobs::create([
						'company_id' => $job->company_id,
						'job_id' => $job->id,
					]);
				}
			}
		}

	}


}

?>
