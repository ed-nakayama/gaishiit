<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Models\Logging;



class del_old_log extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:del_old_log';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'delete old log data';

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
        
		$jobList = Logging::where('updated_at', '<', date('Y-m-d', strtotime('-6 month')) )
			->delete();

	}

}
?>
