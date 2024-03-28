<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;

use App\Models\Job;

use Excel;

use App\Imports\JobsCsvImport; 

use App\Mail\CsvError;

class CompJobsCsv extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:compjobscsv';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'update job info';

	private $job;
	private $error;

	private $CSV_DIR      = 'public/comp_jobs/csv';
	private $BACKUP_DIR   = 'public/comp_jobs/csv/backup';
	private $LOG_DIR      = 'public/comp_jobs/csv/logs';
	
	private $mail_addr = 'nakayama@aci7.com';
//	private $mail_addr = 'rpa-result@gaishiit.com';

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

		$workName   = $this->LOG_DIR  . "/Working_" . date("Ymd")  . ".log";

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

			$workLog = date("Y/m/d H:i:s") . " Start";
			Storage::disk('local')->append($workName, $workLog);

			$errorName = $this->LOG_DIR  . "/CSV_ERR_"   . date("Ymd_His") . ".csv";


			$errorList = array();
			$arg = 0;


			for ($i = 0; $i < $fileCnt; $i++) {

				$file = Storage::disk('local')->path($files[$i]);
				$baseName = basename($files[$i]);

				$workLog = date("Y/m/d H:i:s") . " Proc " . $baseName;
				Storage::disk('local')->append($workName, $workLog);

				$import = new JobsCsvImport();
				Excel::import($import, $file);
			
				$data = $import->sheetData;


				$error_cnt = 0;

				foreach ($data as $line) {

					$status = $line['status'];
					$job_id = $line['job_id'];
					rtrim($status);
					rtrim($job_id);

					$this->check_error($line);

					if (!empty($this->error) ) {
						if ($error_cnt == 0) {
							$error_log = "企業名,JD Status,外資Job番号,備考,エラー理由";
							Storage::disk('local')->append($errorName, $error_log);
							$error_cnt++;
						}

						$error_log = $line['comp_name'] . "," . $line['status'] . "," . $line['job_id'] . "," . $line['comment'] . "," . $this->error;
						Storage::disk('local')->append($errorName, $error_log);

					} else {
						$this->job->open_flag = 1;
						$this->job->open_date = date("Y-m-d H:i:s");
						$this->job->save();
					}
				} // end foreach

end_proc:
				if ($error_cnt > 0) {
					$attachFiles[] = $errorName;
				}

				if (!empty($attachFiles[0]) ) {
					Mail::send(new CsvError($this->mail_addr ,$attachFiles));
				}

				// ファイルをbackupに移動
				$orgFile = $this->BACKUP_DIR . '/' . $baseName;
				Storage::delete($orgFile);
				Storage::move($files[$i], $orgFile);
			} // end for


			$workLog = date("Y/m/d H:i:s") . " End";
			Storage::disk('local')->append($workName, $workLog);
		}

	}



/*******************************************
* パラメータチェック
********************************************/
    private function check_error($line)
    {
		$this->job = null;
		$this->error = '';

		if (!empty($line['job_id'])) {
			$this->job = Job::find($line['job_id']);

			if (empty($this->job)) { // error
				$this->error .= "指定されたJobが見つからない。";
			}

		} else {
			$this->error .= "外資Job番号が設定されていない。";
		}

		if (!empty($line['status'])) {

			if (strcmp($line['status'],'Open') == 0) {
			} else {
				$this->error .= "JD Statusの値が不正。";
			}
		
		} else {
			$this->error .= "JD Statusが設定されていない。";
		}
	}

}

?>
