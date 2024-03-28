<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;

use App\Models\Job;

use Excel;

use App\Imports\JobsCsvImport; 
use App\Exports\JobsExport;

use App\Mail\ExcelError;
use App\Mail\JobClose;

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

	private $CSV_DIR      = 'public/comp_jobs/test';
	

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

		$allFiles = Storage::files($this->CSV_DIR);
		$fileCnt = count($allFiles);
		sort($allFiles);


		$files = array();
		
		for ($i = 0; $i < $fileCnt; $i++) {
			$file = Storage::disk('local')->path($allFiles[$i]);
			$filepath = pathinfo($file);
			
			if (strcmp($filepath['extension'] ,'csv') == 0) {
				$files[] = $allFiles[$i];
			}
		}

		$fileCnt = count($files);
		if ($fileCnt > 0) {
			for ($i = 0; $i < $fileCnt; $i++) {
				$file = Storage::disk('local')->path($files[$i]);
				$baseName = basename($files[$i]);

				$import = new JobsCsvImport();
				Excel::import($import, $file);
			
				$data = $import->sheetData;

	print_r($data);
			}
		}
	}




}

?>
