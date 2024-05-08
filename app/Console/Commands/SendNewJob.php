<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;

use App\Models\Job;
use App\Models\LastUpdate;

use App\Mail\SendNewJobMail;


class SendNewJob extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:sendNewJob';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'send new job';

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
        $lastUpdate = LastUpdate::find(1);
        
        $jobList = Job::join('companies', 'jobs.company_id', 'companies.id')
			->whereNull('companies.deleted_at')
			->whereNull('jobs.deleted_at')
			->where('jobs.open_flag', '1')
			->whereNull('jobs.job_cat_details')
			->where('jobs.created_at', '>', $lastUpdate->last_date)
			->selectRaw('companies.id as comp_id, companies.name as comp_name, jobs.id as job_id, jobs.name as job_name')
			->get();

		$workName  = "public/comp_jobs/new_jobs_" . date("Ymd")  . ".csv";

		Storage::delete($workName);

		$header = 'comp_name,job_name,url';
		Storage::disk('local')->append($workName, $header);
		
		foreach ($jobList as $job) {
			$content = str_replace("\n", '', $job->comp_name) . ',"' . $job->job_name. '"' . ",https://gaishiit.com/admin/mypage/job/edit?company_id={$job->comp_id}&job_id={$job->job_id}";

			$content = mb_convert_encoding($content, 'SJIS-WIN', 'UTF8');

			Storage::disk('local')->append($workName, $content);
		}

		Mail::send(new SendNewJobMail($workName));

		$lastUpdate->last_date = date("Y-m-d H:i:s");
		$lastUpdate->save();
    }


}
