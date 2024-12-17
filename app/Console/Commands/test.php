<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Models\Job;

class Test extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:test';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'test';

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

		$jobList = Job::withTrashed()
			->where('company_id' , '10000004')
			->where('name', 'Modern Application Developer')
			->orderBy('id', 'DESC')
			->limit(1)
			->get();

		foreach ($jobList as $job) {
			print_r($job->id . ' :  ' . $job->company_id  . ' :  ' . $job->name  . ' : ' . $job->deleted_at . "\n");
			$job->deleted_at = null;
			$job->save();
		}
	}




}

?>
