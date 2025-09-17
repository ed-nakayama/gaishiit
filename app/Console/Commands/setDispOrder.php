<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Models\Job;

class SetDispOrder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:setdisporder';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'set diplay order';

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
        $jobList = Job::where('open_flag' ,'1')
        	->orderByRaw('RAND()')
        	->get();

		foreach ($jobList as $job) {
			$job->disp_order = random_int(1, 99999999);
			$job->save();
		}

	}


}

?>
