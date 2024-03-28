<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use Illuminate\Support\Facades\Storage;

use App\Models\Job;

use Excel;

use App\Imports\JobsImport; 
use App\Exports\JobsExport;


class CompJobsExcelDel extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:compjobsexceldel';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'get company job del';

	private $LOG_DIR      = 'public/comp_jobs/logs';
	private $XLSX_LOG_DIR = 'comp_jobs/logs';
	
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
		$workName   = $this->LOG_DIR  . "/" . "Working_" . date("Ymd")  . ".log";


		// 過去ジョブ
		$closeJob = Job::Join('companies', 'jobs.company_id', '=', 'companies.id')
			->leftJoin('units', 'jobs.unit_id', '=', 'units.id')
			->selectRaw('jobs.* ,companies.id as company_id , companies.name as company_name ,units.name as unit_name')
			->where('jobs.updated_at', '<', date("Y-m-d 00:00:00", strtotime("-2 week")))
			->orderBy('jobs.company_id')
			->get();

		if (!empty($closeJob[0])) {

			$jobCnt = count($closeJob);
			print_r("削除対象件数：" . $jobCnt . "\n");

			$workLog = date("Y/m/d H:i:s") . " Start";
			Storage::disk('local')->append($workName, $workLog);

			$delName   = 'GaishiIt_DEL_'   . date("Ymd_His") . ".xlsx";
			$exDelFile = $this->XLSX_LOG_DIR . "/" . $delName;

			$delList = array();
			foreach ($closeJob as $clsJob) {
				$clsJob->delete();

				$delList[] = $this->add_del($clsJob);
			}

			// 削除エラー出力
			if (!empty($delList[0]) ) {
				$view = view('admin.export_del' ,compact(
					'delList',
				));

				Excel::store(new JobsExport($view), $exDelFile, 'public');


				$workLog = date("Y/m/d H:i:s") . " Proc " . $delName;
				Storage::disk('local')->append($workName, $workLog);

			}

			$workLog = date("Y/m/d H:i:s") . " End";
			Storage::disk('local')->append($workName, $workLog);
		}


	}


/*******************************************
* 削除リスト書き出し
********************************************/
    private function add_del($job) {

		$ret_job = array();
		
		$ret_job['comp_id']       = $job->company_id;
		$ret_job['comp_name']     = $job->company_name;
		$ret_job['unit_name']     = $job->unit_name;
		$ret_job['job_code']      = $job->job_code;
		$ret_job['job_title']     = $job->name;
		$ret_job['job_detail']    = $job->intro;
		$ret_job['working_place'] = $job->working_place;

		return $ret_job;
	}


}

?>
