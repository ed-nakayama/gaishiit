<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use Illuminate\Support\Facades\Storage;

class DelOldFiles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:deloldfiles';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'delete old files';

	private $BACKUP_DIR   = 'public/comp_jobs/backup';
	private $LOG_DIR      = 'public/comp_jobs/logs';
	private $JOB_DIR      = 'public/comp_jobs/joblist';
	

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

		$this->del_files($this->LOG_DIR);
		$this->del_files($this->BACKUP_DIR);
		$this->del_files($this->JOB_DIR);
	}


/*******************************************
* 古いログ削除
********************************************/
    private function del_files($dir) {


		collect(Storage::listContents($dir, true))
		->each(function($file) {

			$time = Storage::lastModified($file['path']);

		    if ($file['type'] == 'file' && $time < now()->subDays(90)->getTimestamp()) {
				Storage::delete($file['path']);
			}
		});

	}


}

?>
