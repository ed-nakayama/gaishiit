<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;

use App\Models\Job;
use App\Models\Company;

use App\Mail\SendCurrentJobMail;


class SendCurrentJob extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:sendCurrentJob';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'send current job';

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
		$updateList = Job::join('companies', 'jobs.company_id', 'companies.id')
			->whereNull('companies.deleted_at')
			->whereNull('jobs.deleted_at')
			->selectRaw("companies.id as comp_id, companies.name as comp_name, date_format(jobs.updated_at, '%Y-%m-%d') as updated_at, count(*) as count")
			->groupBy('companies.id')
			->groupBy('companies.name')
			->groupByRaw("date_format(jobs.updated_at, '%Y-%m-%d')")
			->get();

		$updateName  = "public/update_jobs/update_jobs_" . date("Ymd")  . ".csv";

		Storage::delete($updateName);

		$header = 'comp_id,comp_name,updated_at,count';
		Storage::disk('local')->append($updateName, $header);
		
		foreach ($updateList as $job) {
			$content = $job->comp_id  . ',"' . $job->comp_name. '","' .  $job->updated_at . '",' . $job->count;

			$content = mb_convert_encoding($content, 'SJIS-WIN', 'UTF8');

			Storage::disk('local')->append($updateName, $content);
		}


		$deleteList = Job::withTrashed()
			->join('companies', 'jobs.company_id', 'companies.id')
			->whereNull('companies.deleted_at')
			->whereNotNull('jobs.deleted_at')
			->selectRaw("companies.id as comp_id, companies.name as comp_name, date_format(jobs.deleted_at, '%Y-%m-%d') as deleted_at, count(*) as count")
			->groupBy('companies.id')
			->groupBy('companies.name')
			->groupByRaw("date_format(jobs.deleted_at, '%Y-%m-%d')")
			->get();

		$deleteName  = "public/update_jobs/delete_jobs_" . date("Ymd")  . ".csv";

		Storage::delete($deleteName);

		$header = 'comp_id,comp_name,deleted_at,count';
		Storage::disk('local')->append($deleteName, $header);
		
		foreach ($deleteList as $job) {
			$content = $job->comp_id  . ',"' . $job->comp_name. '","' .  $job->deleted_at . '",' . $job->count;

			$content = mb_convert_encoding($content, 'SJIS-WIN', 'UTF8');

			Storage::disk('local')->append($deleteName, $content);
		}

		Mail::send(new SendCurrentJobMail($updateName, $deleteName));
    }


}
