<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use Illuminate\Support\Facades\Storage;

use App\Models\Job;

use Excel;

use App\Exports\JobsExportAI; 


class JobsExportExcelAI extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:jobsExportExcelAI';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'export job for AI';

	private $XLSX_DIR = 'excel';
	
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

		$AIName = 'AI_'   . date("Ymd_His") . ".xlsx";
		$exAIFile = $this->XLSX_DIR . "/" . $AIName;

		$jobList = Job::Join('companies', 'jobs.company_id','=','companies.id')
			->where('companies.open_flag' , '1')
			->where('jobs.open_flag' , '1')
			->whereNull('jobs.app_contents')
			->selectRaw('jobs.id, jobs.intro')
			->get();


		if (!empty($jobList[0]) ) {
			$view = view('excel.export_ai' ,compact(
				'jobList',
			));

			Excel::store(new JobsExportAI($view), $exAIFile, 'public');
		}

	}


}

?>
