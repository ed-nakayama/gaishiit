<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use Illuminate\Support\Facades\Storage;

use App\Models\Job;

use App\Http\Controllers\Admin\MypageController;
use App\Models\ConstLocation;


class dl_sfc extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:dl_sfc';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'download sfc file';

	private $CSV_DIR = 'public/comp_jobs/joblist';
	private $SFC_DIR = 'public/comp_jobs/sfc';

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
        
		$jobList = Job::Join('companies', 'jobs.company_id','=','companies.id')
			->leftJoin('units', 'jobs.unit_id','=','units.id')
			->selectRaw('jobs.*, companies.salesforce_id as salesforce_id, companies.name_english as comp_name_english , units.name as unit_name')
			->whereNotNull('salesforce_id')
			->where('salesforce_id' , '!=', '')
			->orderBy('jobs.company_id')
			->orderBy('jobs.id')
			->get();

			$dir = date("Ymd_His");
			$fileName = date("Ymd");

			$csvFile = $this->CSV_DIR . "/" . $fileName . "_" . $dir . ".csv";

			$files = Storage::files($this->SFC_DIR);
			Storage::delete($files);

			$this->make_sfccsv($csvFile ,$jobList);

			$this->div_csv($csvFile ,$fileName);

	}


/*************************************
* ジョブ一覧CSV作成
**************************************/
	public function make_sfccsv($csvFile ,$jobList) {

		$arg = 0;
		foreach ($jobList as $job) {
			if (!empty($job->locations)) {

				$loc = explode(",", $job->locations);

				$str = array();
				for ($i = 0; $i < count($loc); $i++) {
					$temp = ConstLocation::find($loc[$i]);

					if (!empty($temp->name)) $str[] = $temp->name;
				}

				if ($job->remote_flag == '1') $str[] = 'Remote';
				
				$jobList[$arg++]->locations = implode(";", $str);

			} else {
				$jobList[$arg++]->locations = '';
			}
		}

		$header = '"Client","企業名","Job title","Job title名","JD No.","部署名","職種名","カテゴリー","Location","(Location)","JD URL","職務内容／Job duties","G-URL","JD Status","For agent/エージェント","自動Close","外資Job番号","Event/Job"';
		$header = mb_convert_encoding($header, 'SJIS-WIN', 'UTF8');
		Storage::append($csvFile, $header);


		foreach ($jobList as $job) {

			// 半角スペース 変換
			$job_name      = str_replace( "\xc2\xa0", " ", $job->name );
			$job_code      = str_replace( "\xc2\xa0", " ", $job->job_code );
			$unit_name     = str_replace( "\xc2\xa0", " ", $job->unit_name );
			$job_cat_name  = str_replace( "\xc2\xa0", " ", $job->getJobCategoryName() );
			$sub_category  = str_replace( "\xc2\xa0", " ", $job->sub_category );
			$intro         = str_replace( "\xc2\xa0", " ", $job->intro );
			$working_place = str_replace( "\xc2\xa0", " ", $job->working_place );
			$url           = str_replace( "\xc2\xa0", " ", $job->url );
			$for_agent     = str_replace( "\xc2\xa0", " ", $job->for_agent );

			$job_name      = str_replace( ",", "，" ,$job_name );
			$job_code      = str_replace( ",", "，" ,$job_code );
			$unit_name     = str_replace( ",", "，" ,$unit_name );
			$job_cat_name  = str_replace( ",", "，" ,$job_cat_name );
			$sub_category  = str_replace( ",", "，" ,$sub_category );
			$intro         = str_replace( ",", "，" ,$intro );
			$working_place = str_replace( ",", "，" ,$working_place );
			$for_agent     = str_replace( ",", "，" ,$for_agent );

			$sub_category  = str_replace( "\n ",'<br>' ,$sub_category );
			$intro         = str_replace( "\n" ,'<br>' ,$intro );
			$working_place = str_replace( "\n" ,'<br>' ,$working_place );
			$url           = str_replace( "\n" ,'<br>' ,$url );
			$for_agent     = str_replace( "\n" ,'<br>' ,$for_agent );

			$job_name      = str_replace( '"'  ,''     ,$job_name );
			$unit_name     = str_replace( '"'  ,''     ,$unit_name );
			$job_cat_name  = str_replace( '"'  ,''     ,$job_cat_name );
			$sub_category  = str_replace( '"'  ,''     ,$sub_category );
			$intro         = str_replace( '"'  ,''     ,$intro );
			$working_place = str_replace( '"'  ,''     ,$working_place );
			$for_agent     = str_replace( '"'  ,''     ,$for_agent );

			$location_name = str_replace( '東京'     ,'Tokyo'   ,$job->locations );
			$location_name = str_replace( '大阪'     ,'Osaka'   ,$location_name );
			$location_name = str_replace( '名古屋'   ,'Nagoya'  ,$location_name );
			$location_name = str_replace( '福岡'     ,'Fukuoka' ,$location_name );
			$location_name = str_replace( 'その他'   ,'Other'   ,$location_name );
//			$location_name = str_replace( 'リモート' ,'Remote'  ,$location_name );

			$year = substr($job->created_at ,2 ,2);
			$job_id_pad = $year . str_pad($job->id, 8, 0, STR_PAD_LEFT);

			if (empty($job_code) ) {
				$job_code_hd = "";
			} else {
				$job_code_hd = $job_code . " ";
			}

			$job_id_ft = ' [' . $job_id_pad . ']';
			
			$dif_len = mb_strlen($job_code_hd)  + mb_strlen($job_id_ft);
			$len = 80 - $dif_len;
			$job_short_name = mb_substr($job_name ,0 ,$len);

			// コード変換
			$job_name       = mb_convert_encoding($job_name       ,'SJIS-WIN' ,'UTF-8');
			$job_short_name = mb_convert_encoding($job_short_name ,'SJIS-WIN' ,'UTF-8');
			$job_code       = mb_convert_encoding($job_code       ,'SJIS-WIN' ,'UTF-8');
			$unit_name      = mb_convert_encoding($unit_name      ,'SJIS-WIN' ,'UTF-8');
			$job_cat_name   = mb_convert_encoding($job_cat_name   ,'SJIS-WIN' ,'UTF-8');
			$sub_category   = mb_convert_encoding($sub_category   ,'SJIS-WIN' ,'UTF-8');
			$working_place  = mb_convert_encoding($working_place  ,'SJIS-WIN' ,'UTF-8');
			$url            = mb_convert_encoding($url            ,'SJIS-WIN' ,'UTF-8');
			$intro          = mb_convert_encoding($intro          ,'SJIS-WIN' ,'UTF-8');
			$location_name  = mb_convert_encoding($location_name  ,'SJIS-WIN' ,'UTF-8');
			$for_agent      = mb_convert_encoding($for_agent      ,'SJIS-WIN' ,'UTF-8');
			$auto_close     = mb_convert_encoding('対象'          ,'SJIS-WIN' ,'UTF-8');

			$job_code_hd    = mb_convert_encoding($job_code_hd    ,'SJIS-WIN' ,'UTF-8');
		
			if ($job->open_flag == '1') {
				$open_kbn = 'open';
			} else {
				$open_kbn = mb_convert_encoding('一般Web情報','SJIS-WIN' ,'UTF-8');
			}


			$g_url = "https://gaishiit.com/admin/mypage/job/edit?company_id=" . $job->company_id . "&job_id=" . $job->id;


			$detail =  '"' . $job->salesforce_id . '"'
					. ',"' . $job->comp_name_english . '"'
					. ',"' . $job_code_hd . $job_name  . '"'
					. ',"' . $job_code_hd . $job_short_name  . $job_id_ft . '"'
					. ',"' . $job_code       . '"'
					. ',"' . $unit_name      . '"'
					. ',"' . $job_cat_name   . '"'
					. ',"' . $sub_category   . '"'
					. ',"' . $location_name  . '"'
					. ',"' . $working_place  . '"'
					. ',"' . $url            . '"'
					. ',"' . $intro          . '"'
					. ',"' . $g_url          . '"'
					. ',"' . $open_kbn       . '"'
					. ',"' . $for_agent      . '"'
					. ',"' . $auto_close     . '"'
					. ',"' . $job->id        . '"'
					. ',"' . $job->event_job . '"'
					;

			Storage::append($csvFile, $detail);
		}

	}


/*************************************
* ジョブ一覧CSV作成
**************************************/
	public function div_csv($csvFile ,$fileName) {

		$file_path = Storage::disk('local')->path($csvFile);

		$fp = fopen($file_path, 'r');

		$header = fgets($fp);
		$header = str_replace( "\n", "", $header);

		$cnt = 0;
		$num = 0;

		while (!feof($fp)) {
			if ($cnt % 1000 == 0) {
				$sub_name = $fileName . '_' . str_pad($num, 2, 0, STR_PAD_LEFT) . '.csv';

				$sfcCsv = $this->SFC_DIR . '/' . $sub_name;

				Storage::append($sfcCsv, $header);
				$num++;
			}

			$line = fgets($fp);
			$line = str_replace( "\n", "", $line );
			Storage::append($sfcCsv, $line);

			$cnt++;
		}

	}


}

?>
