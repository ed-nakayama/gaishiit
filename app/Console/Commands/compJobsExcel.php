<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;

use App\Models\Company;
use App\Models\CompMember;
use App\Models\Unit;
use App\Models\Job;
use App\Models\JobCatDetail;
use App\Models\Rpa;

use Excel;

use App\Imports\JobsImport; 
use App\Exports\JobsExport;

use App\Mail\ExcelError;
use App\Mail\ExcelError2;
use App\Mail\JobClose;
use App\Mail\ExcelOk;

class CompJobsExcel extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:compjobsexcel';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'get company job info';

	private $member_id;
	private $member_name;
	private $cat_id;
	private $sub_category;
	private $unit_id;
	private $locations;
	private $jobDetail;
	private $comp;
	private $remote_flag;
	private $open_flag;
	private $open_date;
	private $event_job;

	private $error;

	private $CSV_DIR      = 'public/comp_jobs';
	private $BACKUP_DIR   = 'public/comp_jobs/backup';
	private $LOG_DIR      = 'public/comp_jobs/logs';
	private $XLSX_LOG_DIR = 'comp_jobs/logs';
	private $MAIL_DIR      = 'public/comp_jobs/mail';
	
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
		$delFile = '';
		$attachFiles = array();

		$mail_addr = config('mail.rpa_mail');

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
//				if (strpos($filepath['filename'],'[Open]') === false) {
					$files[] = $allFiles[$i];
//				}
			}
		}

		$fileCnt = count($files);
		if ($fileCnt > 0) {

			$workLog = date("Y/m/d H:i:s") . " Start";
			Storage::disk('local')->append($workName, $workLog);

			$errorName = 'RPA_ERR_'   . date("Ymd_His") . ".xlsx";
			$delName   = 'RPA_DEL_'   . date("Ymd_His") . ".xlsx";
			$eventName = 'RPA_EVENT_' . date("Ymd_His") . ".xlsx";

			$exErrorFile  = $this->XLSX_LOG_DIR . "/" . $errorName;
			$exDelFile    = $this->XLSX_LOG_DIR . "/" . $delName;
			$exEventFile  = $this->XLSX_LOG_DIR . "/" . $eventName;

			$errorFile = $this->LOG_DIR       . "/" . $errorName;
			$delFile   = $this->LOG_DIR       . "/" . $delName;
			$eventFile = $this->LOG_DIR       . "/" . $eventName;

			$errorList = array();
			$eventList = array();
			$arg = 0;
			$eventArg = 0;

			for ($i = 0; $i < $fileCnt; $i++) {

				$file = Storage::disk('local')->path($files[$i]);
				$baseName = basename($files[$i]);

				$workLog = date("Y/m/d H:i:s") . " Proc " . $baseName;
				Storage::disk('local')->append($workName, $workLog);

				$size = Storage::size($files[$i]);

				if ($size == 0) {
					Mail::send(new ExcelError2($mail_addr ,$files[$i]));

					// ファイルをbackupに移動
					$orgFile = $this->BACKUP_DIR . '/' . $baseName;
					Storage::delete($orgFile);
					Storage::move($files[$i], $orgFile);

					continue;
				}


				// ファイル読み込み
				$import = new JobsImport();
				Excel::import($import, $file);
			
				$data = $import->sheetData;

			// コード変換
//				$data = mb_convert_encoding($data, 'UTF8', 'ASCII,JIS,UTF-8,EUC-JP,SJIS-WIN');

				$comp_id = '';
				$comp_name = '';
				$comp_error_flag = 0;

				foreach ($data as $job_arr) {

					if (
						(empty($job_arr['comp_id']) ) && 
						(empty($job_arr['comp_name']) ) && 
						(empty($job_arr['unit_name']) ) && 
						(empty($job_arr['cat_name']) ) && 
						(empty($job_arr['job_id']) ) && 
						(empty($job_arr['job_title']) ) && 
						(empty($job_arr['job_detail']) ) && 
						(empty($job_arr['job_detail_2']) ) && 
						(empty($job_arr['job_detail_3']) ) && 
						(empty($job_arr['job_detail_4']) ) && 
						(empty($job_arr['job_detail_5']) ) && 
						(empty($job_arr['working_place']) ) && 
						(empty($job_arr['register_date']) ) && 
						(empty($job_arr['agent']) ) && 
						(empty($job_arr['url']) ) && 
						(empty($job_arr['kind']) ) && 
						(empty($job_arr['open']) ) && 
						(empty($job_arr['portal']) )
						) {
							continue;
						}

					if (empty($comp_id)) {
						$comp_id = $job_arr['comp_id'];
						$comp_name = $job_arr['comp_name'];
					}
					
					$tempJobDetail = $job_arr['job_detail'];

					if (!empty($job_arr['job_detail_2']) ) {
						$tempJobDetail = $tempJobDetail . "\n\n" . $job_arr['job_detail_2'] ;
					}
					if (!empty($job_arr['job_detail_3']) ) {
						$tempJobDetail = $tempJobDetail . "\n\n" . $job_arr['job_detail_3'] ;
					}
					if (!empty($job_arr['job_detail_4']) ) {
						$tempJobDetail = $tempJobDetail . "\n\n" . $job_arr['job_detail_4'] ;
					}
					if (!empty($job_arr['job_detail_5']) ) {
						$tempJobDetail = $tempJobDetail . "\n\n" . $job_arr['job_detail_5'] ;
					}
					
					$this->jobDetail = rtrim($tempJobDetail);

					$this->check_error($job_arr);

					if (!empty($this->error) ) {
						$comp_error_flag = 1;
						$errorList[$arg] = $job_arr;
						$errorList[$arg++]['reason'] = $this->error;

					} else {
						$this->set_unit($job_arr);
						$this->set_location($job_arr);

						if (!empty($job_arr['job_id'])) { // jobID あり
							$job = Job::withTrashed()
								->where('company_id' ,$job_arr['comp_id'])
								->where('job_code' ,$job_arr['job_id'])
								->orderBy('id', 'DESC')
								->first();
				
						} else { // job タイトル & URL

							$cnt = Job::withTrashed()
								->where('company_id' ,$job_arr['comp_id'])
								->where('url' ,$job_arr['url'])
								->where('name' ,$job_arr['job_title'])
								->where(function($query) {
									$query->whereNull('job_code')
									->orWhere('job_code' , '');
								})
								->count();
					
							if ($cnt == 0) {
								$job = null;
							
							} elseif ($cnt == 1) {
								$job = Job::withTrashed()
									->where('company_id' ,$job_arr['comp_id'])
									->where('url' ,$job_arr['url'])
									->where('name' ,$job_arr['job_title'])
									->where(function($query) {
										$query->whereNull('job_code')
										->orWhere('job_code' , '');
									})
									->first();
						
							} else {
								$job = Job::withTrashed()
									->where('company_id' ,$job_arr['comp_id'])
									->where('url' ,$job_arr['url'])
									->where('name' ,$job_arr['job_title'])
									->where('intro' ,$this->jobDetail)
									->where(function($query) {
										$query->whereNull('job_code')
										->orWhere('job_code' , '');
									})
									->orderBy('id', 'DESC')
									->first();
							}
						}

						// ジョブタイトル
						$jobTitle = $job_arr['job_title'];
						if ( (strpos($jobTitle,'障がい者') === false) && (strpos($jobTitle,'Internship') === false) ) {
							if (empty($job)) {
								$this->create_job($job_arr);
							} else {
								$this->update_job($job ,$job_arr);
							}
						}
					}
				} // end foreach

end_proc:
				// ファイルをbackupに移動
				$orgFile = $this->BACKUP_DIR . '/' . $baseName;
				Storage::delete($orgFile);
				Storage::move($files[$i], $orgFile);

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
			
			if (!empty($eventList[0]) ) {
				$view = view('admin.export_event' ,compact(
					'eventList',
				));

				Excel::store(new JobsExport($view), $exEventFile, 'public');

				$attachFiles[] = $eventFile;
			}

			if (!empty($errorList[0]) || !empty($eventList[0]) ) {
				Mail::send(new ExcelError($mail_addr ,$attachFiles));
			} else {
				if (!empty($comp_id) && $comp_id == '10000001') {
					Mail::send(new ExcelOk($mail_addr ,$comp_name));
				}
			}

			// 削除エラー出力
			if (!empty($delList[0]) ) {
				$view = view('admin.export_del' ,compact(
					'delList',
				));

				Excel::store(new JobsExport($view), $exDelFile, 'public');
			}

			$workLog = date("Y/m/d H:i:s") . " End";
			Storage::disk('local')->append($workName, $workLog);
		}

	}



/*******************************************
* 新規作成
********************************************/
    private function create_job($job_arr)
    {
		print_r("新規　OK  \n");

		$this->set_open($job_arr);

		$rpa = Rpa::find(1);

		$keyword = explode(",", $rpa->keyword);

		$cnt = count($keyword);
		for ($i = 0; $i < $cnt; $i++) {
			if(strpos($job_arr['job_title'], $keyword[$i]) !== false) {
				$this->open_flag = 0;
				$this->open_date = null;
			}
		}


		$job = Job::create([
			'company_id'        => $job_arr['comp_id'],
			'unit_id'           => $this->unit_id,
			'member_id'         => $this->member_id,
			'name'              => $job_arr['job_title'],
			'intro'             => $this->jobDetail,
			'job_code'          => $job_arr['job_id'],
//			'job_cat_detail_id' => $this->cat_id,
			'job_cat_details'   => !empty($this->cat_id) ? "[{$this->cat_id}]" : null,
			'sub_category'      => $this->sub_category,
			'locations'         => $this->locations,
//			'else_location'     => $request->else_location,
			'working_place'     => $job_arr['working_place'],
			'remote_flag'       => $this->remote_flag,
			'register_date'     => $job_arr['register_date'],
			'url'               => $job_arr['url'],
			'casual_flag'       => '1',
			'person'            => $this->member_id,
			'backg_flag'        => $this->comp->backg_flag,
			'backg_eng_flag'    => $this->comp->backg_eng_flag,
			'personal_flag'     => $this->comp->personal_flag,
			'open_flag'         => $this->open_flag,
			'open_date'         => $this->open_date,
			'for_agent'         => $job_arr['agent'],
			'event_job'         => $this->event_job,
			'portal_flag'       => !empty($job_arr['portal'] == '1') ? '1' : '0',
		]);

	}


/*******************************************
* 更新
********************************************/
    private function update_job($job ,$job_arr)
    {
		print_r("更新　OK JobID=" . $job->id . "\n");

		$portal_flag = !empty($job_arr['portal'] == '1') ? '1' : '0';

		$this->set_open($job_arr);

		if (empty($job->open_date)) {
			$job->open_flag = $this->open_flag;
			$job->open_date = $this->open_date;
		}

		$rpa = Rpa::find(1);

		$keyword = explode(",", $rpa->keyword);

		$cnt = count($keyword);
		for ($i = 0; $i < $cnt; $i++) {
			if(strpos($job_arr['job_title'], $keyword[$i]) !== false) {
				$job->open_flag = 0;
				$job->open_date = null;
			}
		}


		if ($job->portal_flag == '0') {
			$job->portal_flag = $portal_flag;
		}

		if ($portal_flag == '0') {
			if ($job->no_auto_flag == '0') {
				$job->locations = $this->locations;
				$job->remote_flag = $this->remote_flag;
			}

			$job->intro = $this->jobDetail;
			$job->working_place = $job_arr['working_place'];
			$job->sub_category = $this->sub_category;
			$job->for_agent = $job_arr['agent'];
			$job->event_job = $this->event_job;

			if ($job->portal_flag == '0') {
				$job->url = $job_arr['url'];
			} else {
				if (!empty($job->deleted_at)) {
					$job->url = $job_arr['url'];
				}
			}

		} else {
			if (!empty($job_arr['url'])) {
				$job->url = $job_arr['url'];
			}
		}
		
		$job->updated_at = date("Y-m-d H:i:s");
		$job->deleted_at = null;
		$job->save();
	}


/*******************************************
* パラメータチェック
********************************************/
    private function check_error(&$job_arr)
    {
		$this->member_id = null;
		$this->member_name = '';
		$this->cat_id = null;
		$this->sub_category = null;
		$this->error = '';

		if (!empty($job_arr['comp_id'])) {
			$this->comp = Company::find($job_arr['comp_id']);

			if (empty($this->comp)) { // error
				$this->error .= "指定された企業IDの企業が見つからない。";

			} else {
				$member = CompMember::where('company_id' ,$job_arr['comp_id'])
					->first();

				if (empty($member)) { // error
//					$this->error .= "企業担当者が設定されていない。";
				} else {
					$this->member_id = $member->id;
					$this->member_name = $member->name;
				}
			}

		} else {
			$this->error .= "企業IDが設定されていない。";
		}

		// 職種ID取得
		if (!empty($job_arr['cat_name'])) {
			$jobCatDetail = JobCatDetail::where('name' ,$job_arr['cat_name'])
				->first();
		
			if (!empty($jobCatDetail)) {
				$this->cat_id = $jobCatDetail->id;
			}

			$this->sub_category = $job_arr['cat_name'];
		}

		// ジョブタイトル
		if (empty($job_arr['job_title'])) {
			$this->error .= "ジョブタイトルがない。";
		}


		if ( $job_arr['kind'] == 'Job' ) {
			$jobTitle = $job_arr['job_title'];
			if (strpos($jobTitle,'合同選考') !== false) {
				if (!empty($this->error)) $this->error .= "／";
				$this->error .= "合同選考が含まれている。";
			}
		}

		if ( !empty($job_arr['kind']) ) {
			if (strcmp($job_arr['kind'] ,'Job') == 0) {
				$this->event_job = 'Job';
			} else if (strcmp($job_arr['kind'] ,'Event') == 0) {
				$this->event_job = 'Event';
			} else {
				$this->error .= "Event/Jobの値が不正です。";
			}
		} else {

			// イベント
			$job_title = $job_arr['job_title'];
			$unit_name = $job_arr['unit_name'];
	//		$detail = $this->jobDetail;
			$detail = $job_arr['cat_name'];
		
			if ( empty($job_arr['kind']) ) {
				if ( (strpos($job_title,'説明会') !== false) || (strpos($job_title,'イベント') !== false) || (strpos($job_title,'セミナー') !== false) || (strpos($job_title,'選考会') !== false) || (strpos($job_title,'合同選考') !== false)
				  || (strpos($unit_name,'説明会') !== false) || (strpos($unit_name,'イベント') !== false) || (strpos($unit_name,'セミナー') !== false) || (strpos($unit_name,'選考会') !== false) || (strpos($unit_name,'合同選考') !== false)
				  || (strpos($detail   ,'説明会') !== false) || (strpos($detail   ,'イベント') !== false) || (strpos($detail   ,'セミナー') !== false) || (strpos($detail   ,'選考会') !== false) || (strpos($detail   ,'合同選考') !== false)
				) {
					$this->event_job = 'Event';

				} else {
					$this->event_job = 'Job';
				}
			}
		}
	
		// ジョブ詳細
		if (empty($job_arr['job_detail'])) {
			if (!empty($this->error)) $this->error .= "／";
			$this->error .= "ジョブ詳細がない。";
		}

		// 勤務地
		$place = $job_arr['working_place'];
		// トリミング
		$place = $this->sp_trim($place);

/* 2022/12/11 削除
		if ( (strpos($place,'海外') !== false) 
			|| (strpos($place,'アメリカ') !== false)
			|| (strpos($place,'北米') !== false)
			|| (strpos($place,'ヨーロッパ') !== false)
		) {
			$this->error .= "勤務地に海外が含まれている。";
		}
*/
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

		if ( mb_strlen($job_arr['unit_name']) > 100 ) {
			if (!empty($this->error)) $this->error .= "／";
			$this->error .= "部署名が長すぎる。";
		}

//		if ( mb_strlen($job_arr['working_place']) > 400 ) {
//			if (!empty($this->error)) $this->error .= "／";
//			$this->error .= "勤務地が長すぎる。";
//		}

		if ( mb_strlen($job_arr['register_date']) > 20 ) {
			if (!empty($this->error)) $this->error .= "／";
			$this->error .= "登録日が長すぎる。";
		}

		if ( mb_strlen($job_arr['agent']) > 800 ) {
			if (!empty($this->error)) $this->error .= "／";
			$this->error .= "エージェント用が長すぎる。";
		}

//		if ( mb_strlen($job_arr['url']) > 600 ) {
//			if (!empty($this->error)) $this->error .= "／";
//			$this->error .= "募集内容URLが長すぎる。";
//		}
		
		
		
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
* 部署セット
********************************************/
    private function set_unit($job_arr) {

		$this->unit_id = null;

		// 部署ID取得
		if (!empty($job_arr['unit_name'])) {
			$unit = Unit::where('company_id' ,$job_arr['comp_id'])
				->where('name' ,$job_arr['unit_name'])
				->first();
					
			if (empty($unit)) { // error
				$unit = Unit::create([
					'company_id'        => $job_arr['comp_id'],
					'name'              => $job_arr['unit_name'],
					'person'            => $this->member_id,
					'open_flag'         => '1',
					'open_date'         => date("Y-m-d H:i:s"),
				]);

				$this->unit_id = $unit->id;

			} else {
				$this->unit_id = $unit->id;
			}
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

//		$front = strstr($place,'※',true);
//		if ($front !== false) {
//			$place = $front;
//		}

//		print_r("<------------\n");
//		print_r($place);
//		print_r("\n");
//		print_r("------------>\n");

		return $place;
	}


/*******************************************
* 勤務地セット
********************************************/
    private function set_location($job_arr) {

		$loc = array();

		// 勤務地
		$exist_flag = 0;
		$this->remote_flag = 0;
		if (!empty($job_arr['working_place'])) {
			$place = $job_arr['working_place'];
			
			if (empty($place)) {
				$loc[] = 1;  // 東京

			} else {
				if ( (strpos($place,'リモート') !== false) || (strpos($place,'在宅') !== false) || (strpos($place,'自宅') !== false) ) {
					$this->remote_flag = 1;

				} else {

					// トリミング
					$place = $this->sp_trim($place);

		
					if ( (strpos($place,'東京') !== false) || (strpos($place,'Tokyo') !== false) ) {
						$loc[] = 1;
						$exist_flag = 1;
					}
			
					if ( (strpos($place,'大阪') !== false) || (strpos($place,'Osaka') !== false) ) {
						$loc[] = 2;
						$exist_flag = 1;
					}

					if ( (strpos($place,'名古屋') !== false) || (strpos($place,'Nagoya') !== false) ) {
						$loc[] = 3;
						$exist_flag = 1;
					}

					if ( (strpos($place,'博多') !== false) || (strpos($place,'Hakata') !== false)
						 || (strpos($place,'福岡') !== false) || (strpos($place,'Fukuoka') !== false)
					 ) {
						$loc[] = 4;
						$exist_flag = 1;
					}
/* 2022/12/11 削除
					if ($exist_flag == 0 ) {
						$loc[] = 99;
					}
*/
				}
			}
		}

		if (!empty($loc[0]) ) {
			$this->locations = implode(',', $loc);
		}

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
			$this->open_flag = 1;
			$this->open_date = date("Y-m-d H:i:s");
		}

		if (strcmp($this->event_job,'Event') == 0) {
			$this->open_flag = 0;
			$this->open_date = null;
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
