<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;

use App\Models\Company;
use App\Models\Job;

use Excel;

use App\Imports\JobsImport; 
use App\Exports\JobsExport;

use App\Mail\ExcelError;
use App\Mail\JobClose;

class CompJobsExcelUpdate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:compjobsexcelupdate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'company job update';

	private $open_flag;
	private $open_date;

	private $error;
	private $noData;

	private $CSV_DIR      = 'public/comp_jobs';
	private $BACKUP_DIR   = 'public/comp_jobs/backup';
	private $LOG_DIR      = 'public/comp_jobs/logs';
	private $XLSX_LOG_DIR = 'comp_jobs/logs';
	private $MAIL_DIR      = 'public/comp_jobs/mail';
	
//	private $mail_addr = 'nakayama@aci7.com';
	private $mail_addr = 'rpa-result@gaishiit.com';

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
		$baseName = '';
		$attachFiles = array();

		$workName   = $this->LOG_DIR  . "/" . "Working_" . date("Ymd")  . ".log";
		$mailName   = $this->MAIL_DIR  . "/" . "mail_log.csv";

		$allFiles = Storage::files($this->CSV_DIR);
		$fileCnt = count($allFiles);
		sort($allFiles);

		$files = array();
		
		for ($i = 0; $i < $fileCnt; $i++) {
			$file = Storage::disk('local')->path($allFiles[$i]);
			$filepath = pathinfo($file);
			
			if (strcmp($filepath['extension'] ,'xlsx') == 0) {
				if (strpos($filepath['filename'],'[Open]') !== false) {
					$files[] = $allFiles[$i];
				}
			}
		}

		$fileCnt = count($files);
		if ($fileCnt > 0) {

			$workLog = date("Y/m/d H:i:s") . " Start";
			Storage::disk('local')->append($workName, $workLog);

			$errorName = 'OPEN_ERR_'   . date("Ymd_His") . ".xlsx";

			$exErrorFile  = $this->XLSX_LOG_DIR . "/" . $errorName;

			$errorFile = $this->LOG_DIR       . "/" . $errorName;

			$errorList = array();
			$arg = 0;

			for ($i = 0; $i < $fileCnt; $i++) {

				$file = Storage::disk('local')->path($files[$i]);
				$baseName = basename($files[$i]);

				$workLog = date("Y/m/d H:i:s") . " Proc " . $baseName;
				Storage::disk('local')->append($workName, $workLog);

				// ファイル読み込み
				$import = new JobsImport();
				Excel::import($import, $file);
			
				$data = $import->sheetData;

			// コード変換
				$data = mb_convert_encoding($data, 'UTF8', 'ASCII,JIS,UTF-8,EUC-JP,SJIS-WIN');

				$comp_id = '';
				$comp_error_flag = 0;

				foreach ($data as $job_arr) {

					if (empty($comp_id)) $comp_id = $job_arr['comp_id'];

					$this->check_error($job_arr);

					if (!empty($this->error) ) {
						$comp_error_flag = 1;
						$errorList[$arg] = $job_arr;
						$errorList[$arg++]['reason'] = $this->error;

					} else {
						if (!empty($job_arr['job_id'])) { // jobID あり
							$jobList = Job::where('job_code' ,$job_arr['job_id'])
								->get();
				
						} else { // job タイトルのみ

							$jobList = Job::where('jobs.company_id' ,$job_arr['comp_id'])
								->where('jobs.name' ,$job_arr['job_title'])
								->where(function($query) {
									$query->whereNull('job_code')
									->orWhere('job_code' , '');
								})
								->get();
						}

						if (!empty($jobList[0])) {
							$this->update_job($jobList ,$job_arr);
						} else {
							$comp = Company::find($job_arr['comp_id']);

							// GaishiITに該当するデータがない。
							$this->create_job($job_arr);
/*
							$temp = '"' . $job_arr['comp_id']   . '",' .
									'"' . $comp->salesforce_id   . '",' .
									'"' . $job_arr['job_title'] . '",' .
									'"' . $job_arr['job_id'];

							if (!Storage::exists($mailName) ) {
								$header = '"企業ID","SFDC ID","Job Title","Job ID"';
								$header2 = mb_convert_encoding($header, 'SJIS', 'ASCII,JIS,UTF-8,EUC-JP,SJIS-WIN');
								Storage::disk('local')->append($mailName, $header2);
							}
							
							$temp2 = mb_convert_encoding($temp, 'SJIS', 'ASCII,JIS,UTF-8,EUC-JP,SJIS-WIN');
							Storage::disk('local')->append($mailName, $temp2);
*/
						}
					}
				} // end foreach

end_proc:

				// ファイルをbackupに移動
				$orgFile = $this->BACKUP_DIR . '/' . $baseName;
				Storage::delete($orgFile);
				Storage::move($files[$i], $orgFile);
//				Storage::copy($files[$i], $orgFile);

				if ($comp_error_flag == 1) {
					$attachFiles[] = $orgFile;
				}
			} // end for

			// エラー出力
			if (!empty($errorList[0]) ) {
				$view = view('admin.export_error' ,compact(
					'errorList',
				));

				Excel::store(new JobsExport($view), $exErrorFile, 'public');

				$attachFiles[] = $errorFile;
			}

			if (!empty($errorList[0]) ) {
				Mail::send(new ExcelError($this->mail_addr ,$attachFiles));
			}


			$workLog = date("Y/m/d H:i:s") . " End";
			Storage::disk('local')->append($workName, $workLog);
		}

	}


/*******************************************
* 更新
********************************************/
    private function update_job($jobList ,$job_arr)
    {

		foreach ($jobList as $job) {

			$this->set_open($job_arr);

			if (empty($job->open_date) && $this->open_flag == 1) {
				print_r("更新　OK JobID=" . $job->id . "   ". $job_arr['open'] . "\n");

				$job->for_agent = $job_arr['agent'];
				$job->open_flag = $this->open_flag;
				$job->open_date = $this->open_date;
			}

			$job->save();
		}
	}


/*******************************************
* 新規作成
********************************************/
    private function create_job($job_arr)
    {

		print_r("新規　OK  \n");

		$this->set_open($job_arr);

		$jobDetail = $job_arr['job_detail'] . "\n\n" . $job_arr['job_detail_2'] . "\n\n" . $job_arr['job_detail_3'] . "\n\n" . $job_arr['job_detail_4'] . "\n\n" . $job_arr['job_detail_5'];

		$job = Job::create([
			'company_id'        => $job_arr['comp_id'],
			'name'              => $job_arr['job_title'],
			'intro'             => $jobDetail,
			'job_code'          => $job_arr['job_id'],
			'working_place'     => $job_arr['working_place'],
			'register_date'     => $job_arr['register_date'],
			'url'               => $job_arr['url'],
			'open_flag'         => $this->open_flag,
			'open_date'         => $this->open_date,
			'for_agent'         => $job_arr['agent'],
		]);

	}



/*******************************************
* パラメータチェック
********************************************/
    private function check_error(&$job_arr)
    {
		$this->error = '';

		if (!empty($job_arr['comp_id'])) {
			$this->comp = Company::find($job_arr['comp_id']);

			if (empty($this->comp)) { // error
				$this->error .= "指定された企業IDの企業が見つからない。";
			}

		} else {
			$this->error .= "企業IDが設定されていない。";
		}

		// ジョブタイトル
		if (empty($job_arr['job_title'])) {
			$this->error .= "ジョブタイトルが設定されていない。";
		}


		///////////////////////////////////////////////
		// 長さチェック
		///////////////////////////////////////////////
		if ( mb_strlen($job_arr['comp_id']) > 8 ) {
			if (!empty($this->error)) $this->error .= "／";
			$this->error .= "企業IDが長すぎる。";
		}

		if ( mb_strlen($job_arr['job_id']) > 20 ) {
			if (!empty($this->error)) $this->error .= "／";
			$this->error .= "募集番号が長すぎる。";
		}

		if ( mb_strlen($job_arr['job_title']) > 200 ) {
			if (!empty($this->error)) $this->error .= "／";
			$this->error .= "ジョブタイトルが長すぎる。";
		}

		$open = ($job_arr['open']);
		$open = $this->sp_trim($open);
		if (empty($open) ) {
		} else if (strcmp($open,'公開') == 0) {
		} else if (strcmp($open,'非公開') == 0) {
		} else {
			$this->error .= "公開区分の値が不正です。";
		}

	}


/*******************************************
* トリミング
********************************************/
    private function sp_trim($place) {

		// トリミング
		while (1) {
			$front = strstr($place,'（',true);
			$end = '';
				
			if ($front !== false) {
				$end = strstr($place,'）',false);
				if ($end !== false) {
					$end = mb_substr($end , 1);
				}

				$place = $front . $end;
			} else {
				break;
			}
		}

		return $place;
	}


/*******************************************
* 公開／非公開セット
********************************************/
    private function set_open($job_arr) {

		$open = ($job_arr['open']);
		$open = $this->sp_trim($open);

		if ( !empty($open) ) {
			if (strcmp($open,'公開') == 0) {
				$this->open_flag = 1;
				$this->open_date = date("Y-m-d H:i:s");
			}
			
			if (strcmp($open,'非公開') == 0) {
				$this->open_flag = 0;
				$this->open_date = null;
			}
		} else {
			$this->open_flag = 0;
			$this->open_date = null;
		}

	}


}

?>
