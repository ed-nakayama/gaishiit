<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

use App\Models\Interview;
use App\Models\Job;
use App\Models\Company;
use App\Models\Unit;
use App\Models\CompMember;
use App\Models\ConstLocation;
use App\Models\Evaluation;
use App\Models\JobCatDetail;
use App\Models\IndustoryCatDetail;
use App\Models\User;

use STS\ZipStream\ZipStreamFacade AS Zip;

class MypageController extends Controller
{
	private $CSV_DIR = 'public/comp_jobs/joblist';
	private $SFC_DIR = 'public/comp_jobs/sfc';

	public function __construct()
    {
        $this->middleware('auth:admin');
    }
    
    
/*************************************
* 一覧 候補者検索
**************************************/
	public function index()
	{
		$request = new Request();
		
		$request->merge(['sel_aprove' => '']);
		$request->merge(['suser' => '']);


		$userList = $this->search_list($request);

		$sel_aprove = '';
		$user_name = '';

		$user = \Auth::user();

		if ($user->agent_priv == '1') {
			return redirect('/admin/mypage/joblist');
		}

		return view('admin.mypage' ,compact(
			'userList',
			'sel_aprove',
			'user_name',
		));
	}


/*************************************
* 一覧 候補者検索
**************************************/
	public function list(Request $request)
	{
		$sel_aprove = $request->sel_aprove;
		$user_name = !empty($request->user_name) ? $request->user_name : '';

		$userList = $this->search_list($request);

		return view('admin.mypage' ,compact(
			'userList',
			'sel_aprove',
			'user_name',
		));

	}


/*************************************
* 検索リスト
**************************************/
	public function search_list($request)
	{
		$userQuery = User::selectRaw("users.*");

		if ($request->sel_aprove == '0') {
			$userQuery = $userQuery->where('aprove_flag' , '0');
			
		} elseif ($request->sel_aprove == '1') {
			$userQuery = $userQuery->where('aprove_flag' , '1');

		} elseif ($request->sel_aprove == '2') {
			$userQuery = $userQuery->where('aprove_flag' , '2');
		}

		if (!empty($request->user_name) ) {
			$user_name = $request->user_name;
			$userQuery = $userQuery->where('name' , 'like', "%{$user_name}%");
		}

		$userList = $userQuery
			->orderBy('created_at' ,'desc')
			->paginate(20);

		return $userList;
	}
	


/*************************************
* 面談進捗管理
**************************************/
	public function progress()
	{
		$comp = Interview::Join('companies', 'interviews.company_id', 'companies.id')
			->where('interviews.interview_type' , '0')
			->where('interviews.interview_kind' , '0')
			->where('interviews.aprove_flag', '1')
			->whereNotIn('interviews.status_id', [4, 9])
			->selectRaw('interviews.*');

		$unit = Interview::join('units', 'interviews.unit_id','=','units.id')
			->where('interviews.interview_type' , '0')
			->where('interviews.interview_kind' , '1')
			->where('interviews.aprove_flag', '1')
			->whereNotIn('interviews.status_id', [4, 9])
			->selectRaw('interviews.*');

		$beingList = Interview::join('jobs','interviews.job_id', 'jobs.id')
			->whereIn('interviews.interview_type', [0, 1])
			->where('interviews.interview_kind' , '2')
			->where('interviews.aprove_flag', '1')
			->whereNotIn('interviews.status_id', [4, 9])
			->selectRaw('interviews.*')

			->union($comp)
			->union($unit)
			->orderBy('updated_at')
			->get();

		$i = 0;
		$cnt = count($beingList);
		for ($i = 0; $i < $cnt; $i++) {
			$beingList[$i]->getUser();
			$beingList[$i]->getCompany();
			$beingList[$i]->getUnit();
			$beingList[$i]->getJob();
		}


		$comp = Interview::Join('companies', 'interviews.company_id', 'companies.id')
			->where('interviews.interview_type' , '0')
			->where('interviews.interview_kind' , '0')
			->where('interviews.aprove_flag', '1')
			->where('interviews.status_id' , '4')
			->selectRaw('interviews.*');

		$unit = Interview::join('units', 'interviews.unit_id','=','units.id')
			->where('interviews.interview_type' , '0')
			->where('interviews.interview_kind' , '1')
			->where('interviews.aprove_flag', '1')
			->where('interviews.status_id' , '4')
			->selectRaw('interviews.*');

		$alreadyList = Interview::join('jobs','interviews.job_id', 'jobs.id')
			->whereIn('interviews.interview_type', [0, 1])
			->where('interviews.interview_kind' , '2')
			->where('interviews.aprove_flag', '1')
			->where('interviews.status_id' , '4')
			->selectRaw('interviews.*')

			->union($comp)
			->union($unit)
			->orderBy('updated_at')
			->get();


		$i = 0;
		$cnt = count($alreadyList);
		for ($i = 0; $i < $cnt; $i++) {
			$alreadyList[$i]->getUser();
			$alreadyList[$i]->getCompany();
			$alreadyList[$i]->getUnit();
			$alreadyList[$i]->getJob();
		}


		return view('admin.mypage_progress' ,compact(
			'beingList',
			'alreadyList',
		));

	}


/*************************************
* 一覧
**************************************/
	public function progress_list(Request $request)
	{
		$loginUser = Auth::user();

		$interview = Interview::find($request->interview_id);
		
		if (!empty($request->status)) {
			$interview->status_id = $request->status;
		} else {
			$interview->status_id = 0;
		}
		
		if (!empty($request->result)) {
			$interview->result_id = $request->result;
		} else {
			$interview->result_id = 0;
		}

		if (!empty($request->stage)) {
			$interview->stage_id = $request->stage;
		} else {
			$interview->stage_id = 0;
		}

		
		$interview->interviewer = $request->interviewer;
		$interview->entrance_date = $request->entrance_date;
		$interview->comment = $request->comment;
		$interview->last_update_id = $loginUser->id;


       if ( $request->has('interview_date') ) {
			$interview->interview_date = $request->interview_date;
		}
		
		$interview->save();

		return redirect('admin/mypage/progress');
	}


/*************************************
* 採用者一覧
**************************************/
	public function mypage_enter()
	{
		$loginUser = Auth::user();
		
		$enterList = $this->enter_search_list();

		return view('admin.mypage_enter' ,compact(
			'enterList',
		));

	}


/*************************************
* 一覧
**************************************/
	public function enter_list(Request $request)
	{
		$loginUser = Auth::user();

		$interview = Interview::find($request->interview_id);

		$interview->entrance_date = $request->entrance_date;
		$interview->last_update_id = $loginUser->id;
		$interview->save();

		return redirect('admin/mypage/enter');
	}


/*************************************
* 検索リスト
**************************************/
	public function enter_search_list()
	{
		$endList = Interview::Join('companies', 'interviews.company_id', 'companies.id')
			->where('companies.agency_flag', '1')
			->where('interviews.interview_type' ,'1')
			->where('interviews.aprove_flag', '1')
			->where('interviews.result_id', '1')
			->where('interviews.status_id', '9')
			->orderByRaw('interviews.entrance_date IS NULL DESC')
			->orderBy('interviews.entrance_date' , 'desc')
			->paginate(20);

		$i = 0;
		$cnt = count($endList);
		for ($i = 0; $i < $cnt; $i++) {
			$endList[$i]->getUser();
			$endList[$i]->getCompany();
			$endList[$i]->getUnit();
			$endList[$i]->getJob();
			$endList[$i]->getStage();
			$endList[$i]->getStatus();
			$endList[$i]->getPerson();
		}

		return $endList;
	}


/*************************************
* ジョブ一覧
**************************************/
	public function joblist()
	{
		$jobList = Job::leftJoin('companies', 'jobs.company_id','=','companies.id')
			->leftJoin('units', 'jobs.unit_id','=','units.id')
			->selectRaw('jobs.*, companies.name as company_name, units.name as unit_name')
//			->where('jobs.open_flag' , '1')
			->orderBy('jobs.updated_at' ,'desc')
			->orderBy('companies.name')
			->orderBy('jobs.name')
			->paginate(20);

		$comp_name = '';
		$job_title = '';
		$sub_category = '';
		$working_place = '';
		$unit_name = '';
		$location = '';
		$freeword = '';
		$open_flag = 2;
		$cat_flag = 2;
		$portal_flag = 2;
		$comp_id = '';

		$user = \Auth::user();

		if ($user->agent_priv == '1') {
			return view('admin.mypage_agent' ,compact(
			'jobList',
			'comp_name',
			'job_title',
			'sub_category',
			'working_place',
			'unit_name',
			'location',
			'freeword',
			'open_flag',
			'cat_flag',
			'portal_flag',
			'comp_id',
			));
		}

		return view('admin.mypage_joblist' ,compact(
			'jobList',
			'comp_name',
			'job_title',
			'sub_category',
			'working_place',
			'unit_name',
			'location',
			'freeword',
			'open_flag',
			'cat_flag',
			'portal_flag',
			'comp_id',
		));

	}


/*************************************
* ジョブ一覧
**************************************/
	public function joblist_list(Request $request)
	{
		$comp_name = $request->comp_name;
		$job_title = $request->job_title;
		$sub_category = $request->sub_category;
		$working_place = $request->working_place;
		$unit_name = $request->unit_name;
		$location = $request->location;
		$freeword = $request->freeword;
		$open_flag = $request->open_flag;
		$cat_flag = $request->cat_flag;
		$portal_flag = $request->portal_flag;
		$comp_id = $request->comp_id;

		if (!empty($request->dl)) {
			$jobList = Job::Join('companies', 'jobs.company_id','=','companies.id')
				->leftJoin('units', 'jobs.unit_id','=','units.id')
				->selectRaw('jobs.*, companies.salesforce_id as salesforce_id, companies.name_english as comp_name_english , units.name as unit_name');

		} else {

			if (!empty($request->bulk)) {
				if ($request->bulk == '1') { // 一括削除
					$closeCheck = $request->closeCheck;

					$cnt = count($closeCheck);
					for ($i = 0; $i < $cnt; $i++) {
						$job = Job::find($closeCheck[$i]);

						$job->delete();
					}
				}
				
				if ($request->bulk == '2') { // 一括表示
					$dispCheck = $request->dispCheck;

					$cnt = count($dispCheck);
					for ($i = 0; $i < $cnt; $i++) {
						$job = Job::find($dispCheck[$i]);

						$job->open_flag = '1';
						$job->save();
					}
				} 
				
				if ($request->bulk == '3') { // 一括非表示
					$dispCheck = $request->dispCheck;

					$cnt = count($dispCheck);
					for ($i = 0; $i < $cnt; $i++) {
						$job = Job::find($dispCheck[$i]);

						$job->open_flag = '0';
						$job->save();
					}
				}
			}

			$jobList = Job::Join('companies', 'jobs.company_id','=','companies.id')
				->leftJoin('units', 'jobs.unit_id','=','units.id')
				->selectRaw('jobs.*, companies.name as company_name, units.name as unit_name');
		}


		if (!empty($comp_id)) {
			$jobList = $jobList->where('jobs.company_id' , $comp_id);
		}


		$words = array();
		if (!empty($comp_name)) {
			$fd = str_replace('　', ' ',$comp_name);
			$words = explode(" ", $fd);

			for ($i = 0; $i < count($words); $i++) {
				$jobList = $jobList
					->where(function($query) use ($words ,$i) {
						$query->where('companies.name' , 'like', '%' . $words[$i] .'%')
						->orWhere('companies.name_english' , 'like', '%' . $words[$i] .'%');
					});

			}
		}


		$words = array();
		if (!empty($job_title)) {
			$fd = str_replace('　', ' ',$job_title);
			$words = explode(" ", $fd);

			for ($i = 0; $i < count($words); $i++) {
				$jobList = $jobList->where('jobs.name' , 'like', '%' . $words[$i] .'%');
			}
		}


		$words = array();
		if (!empty($sub_category)) {
			$fd = str_replace('　', ' ',$sub_category);
			$words = explode(" ", $fd);

			for ($i = 0; $i < count($words); $i++) {
				$jobList = $jobList->where('jobs.sub_category' , 'like', '%' . $words[$i] .'%');
			}
		}


		$words = array();
		if (!empty($working_place)) {
			$fd = str_replace('　', ' ',$working_place);
			$words = explode(" ", $fd);

			for ($i = 0; $i < count($words); $i++) {
				$jobList = $jobList->where('jobs.working_place' , 'like', '%' . $words[$i] .'%');
			}
		}


		$words = array();
		if (!empty($unit_name)) {
			$fd = str_replace('　', ' ',$unit_name);
			$words = explode(" ", $fd);

			for ($i = 0; $i < count($words); $i++) {
				$jobList = $jobList->where('units.name' , 'like', '%' . $words[$i] .'%');
			}
		}

		if (!empty($location)) {
			if ($location == '99') { // 設定なし
				$jobList = $jobList->whereNull('jobs.locations');

				$jobList = $jobList
					->where(function($query) {
						$query->whereNull('jobs.locations')
						->orWhere('jobs.locations' , '');
					});

			} else {
				$jobList = $jobList->where('jobs.locations' , 'like', '%' . $location .'%');
			}
		}

		$words = array();
		if (!empty($freeword)) {
			$fd = str_replace('　', ' ',$freeword);
			$words = explode(" ", $fd);
		}

		for ($i = 0; $i < count($words); $i++) {
			$jobList = $jobList
				->where(function($query) use ($words ,$i) {
					$query->where('jobs.name' , 'like', "%{$words[$i]}%")
					->orWhere('jobs.intro' , 'like', "%{$words[$i]}%")
					->orWhere('jobs.job_code' , 'like', "%{$words[$i]}%")
					->orWhere('jobs.sub_category' , 'like', "%{$words[$i]}%")
					->orWhere('companies.name' , 'like', "%{$words[$i]}%")
					->orWhere('units.name' , 'like', "%{$words[$i]}%")
//					->orWhere('job_cat_details.name' , 'like', "%{$words[$i]}%")
					;
				});
		}

		if ($open_flag == 0 || $open_flag == 1) {
			$jobList = $jobList->where('jobs.open_flag' ,$open_flag);
		}

		if ($cat_flag == 0) {
			$jobList = $jobList->whereNull('jobs.job_cat_details');
		} else if ($cat_flag == 1) {
			$jobList = $jobList->whereNotNull('jobs.job_cat_details');
		}

		if ($portal_flag != 2) {
			$jobList = $jobList->where('jobs.portal_flag' ,$portal_flag);
		}
		
		if (!empty($request->dl)) {
			$jobList = $jobList->orderBy('jobs.company_id')
				->orderBy('jobs.id')
				->get();


			$fileName = "joblist_" . date("Ymd_His") . ".csv";
			$dlFileName = "Gaishi_Joblit.csv";
			$csvFile = $this->CSV_DIR . "/" . $fileName;
			$dlFile = $this->CSV_DIR . "/" . $dlFileName;

			$this->make_sfccsv($csvFile ,$jobList);

			$mimeType = Storage::mimeType($csvFile);
			$headers = [['Content-Type' => $mimeType]];

			Storage::delete($dlFile);
			Storage::copy($csvFile, $dlFile);

			return Storage::download($dlFile, $dlFileName, $headers);

		} else {
			$jobList = $jobList->orderBy('jobs.updated_at' ,'desc')
				->orderBy('companies.name')
				->orderBy('jobs.name')
				->paginate(20);

			$user = \Auth::user();

			if ($user->agent_priv == '1') {
				return view('admin.mypage_agent' ,compact(
				'jobList',
				'comp_name',
				'job_title',
				'sub_category',
				'working_place',
				'unit_name',
				'location',
				'freeword',
				'open_flag',
				'cat_flag',
				'portal_flag',
				'comp_id',
				));
			}

			return view('admin.mypage_joblist' ,compact(
				'jobList',
				'comp_name',
				'job_title',
				'sub_category',
				'working_place',
				'unit_name',
				'location',
				'freeword',
				'open_flag',
				'cat_flag',
				'portal_flag',
				'comp_id',
			));
		}


	}


/*************************************
* 編集
**************************************/
	public function job_edit( Request $request )
	{
		$comp_id = $request->company_id;

 		$memberList = CompMember::select('id', 'name')->where('company_id' , $comp_id)->get();
 		$unitList = Unit::select('id', 'name')->where('company_id' , $comp_id)->get();

		$job = Job::find($request->job_id);

		return view('admin.job_edit' ,compact(
			'job',
			'unitList',
			'memberList',
		));
	}


/*************************************
* 再編集
**************************************/
	public function job_reedit( Request $request )
	{
		$comp_id = $request->company_id;
		$job_id = $request->job_id;

		$request->session()->regenerate();
		$request->session()->regenerateToken(); // CSRFトークンを再生成

		return redirect()->route('admin.mypage.job.edit', [ 'company_id' => $comp_id, 'job_id' => $job_id, ] );
	}


/*************************************
* 参照
**************************************/
	public function job_ref( Request $request )
	{
		$comp_id = $request->company_id;

 		$memberList = CompMember::select('id', 'name')->where('company_id' , $comp_id)->get();
 		$unitList = Unit::select('id', 'name')->where('company_id' , $comp_id)->get();

		$job = Job::find($request->job_id);

		return view('admin.job_ref' ,compact(
			'job',
			'unitList',
			'memberList',
		));
	}


/*************************************
* 状態変更
**************************************/
	public function job_change( Request $request )
	{
		$job = Job::find($request->job_id);

		$comp_id = $request->company_id;

		if ($request->del_flag == '1') {
			$job->delete();
			
		} else {
			if ($request->open_flag == '1') {
				if ($job->open_flag == '0') $job->open_date = date("Y-m-d H:i:s");
				$job->open_flag = 1;
			} else {
				$job->open_flag = 0;
			}

			$job->save();
		}

		if ($request->del_flag == '1') {
			return redirect('admin/mypage/joblist');
		} else {
			return redirect()->route('admin.mypage.job.edit', [ 'company_id' => $comp_id, 'job_id' => $job->id, ] )->with('option_success', '公開情報を保存しました。');
		}
	
	}


/*************************************
* 新規登録
**************************************/
	public function job_post(Request $request)
	{
		$loginUser = Auth::user();

		$comp_id = $request->company_id;

		$validatedData = $request->validate([
			'name'             => ['required', 'string', 'max:100'],
			'intro'             => ['required', 'string'],
			'locations'         => ['required'],
			'person'            => ['required'],
		]);

		if ($request->casual_flag == '1') {
			$casual_flag = 1;
		} else {
			$casual_flag = 0;
		}

		if ($request->remote == '1') {
			$remote_flag = 1;
		} else {
			$remote_flag = 0;
		}

		if ($request->no_auto_flag == '1') {
			$no_auto_flag = 1;
		} else {
			$no_auto_flag = 0;
		}

		$locations = '';
		if (!empty($request->locations) ) {
			$locations = implode(',', $request->locations);
		}
		
		$job = Job::find( $request->job_id);

		$job->unit_id           = $request->unit;
		$job->member_id         = $loginUser->id;
		$job->name              = $request->name;
		$job->intro             = $request->intro;
		$job->job_code          = $request->job_code;
		$job->sub_category      = $request->sub_category;
		$job->locations         = $locations;
		$job->else_location     = $request->else_location;
		$job->remote_flag       = $remote_flag;
		$job->backg_flag        = $request->backg_flag;
		$job->backg_eng_flag    = $request->backg_eng_flag;
		$job->personal_flag     = $request->personal_flag;
		$job->casual_flag       = $casual_flag;
		$job->person            = $request->person;
		$job->working_place     = $request->working_place;
		$job->no_auto_flag      = $no_auto_flag;
		$job->income_id         = $request->income_id;

		// 職種保存
		$job->setJobCat($request->jobCat);
		// インダストリ保存
//		$job->setIndustory($request->indCat);
		$job->setIndustoryCat($request->indCat);

		$job->save();

		return redirect()->route('admin.mypage.job.edit', [ 'company_id' => $comp_id, 'job_id' => $job->id, ] )->with('update_success', 'ジョブ情報を保存しました。');
	}


/*************************************
* ジョブ一覧ダウンロード
**************************************/
	public function joblist_download(Request $request)
	{

		$fileName = "joblist_" . date("Ymd_His") . ".csv";
		$dlFileName = "Gaishi_Joblit.csv";
		$csvFile = $this->CSV_DIR . "/" . $fileName;
		$dlFile = $this->CSV_DIR . "/" . $dlFileName;

		$jobList = Job::Join('companies', 'jobs.company_id','=','companies.id')
			->leftJoin('units', 'jobs.unit_id','=','units.id')
			->selectRaw('jobs.*, companies.salesforce_id as salesforce_id, companies.name_english as comp_name_english , units.name as unit_name')
			->whereNotNull('salesforce_id')
			->where('salesforce_id' , '!=', '')
			->orderBy('jobs.company_id')
			->orderBy('jobs.id')
			->get();

//		$this->make_csv($csvFile);
		$this->make_sfccsv($csvFile ,$jobList);

		$mimeType = Storage::mimeType($csvFile);
		$headers = [['Content-Type' => $mimeType]];

		Storage::delete($dlFile);
		Storage::copy($csvFile, $dlFile);

		return Storage::download($dlFile, $dlFileName, $headers);
	}


/*************************************
* ジョブ一覧CSV作成
**************************************/
	public function make_csv($csvFile) {

		$jobList = Job::Join('companies', 'jobs.company_id','=','companies.id')
			->leftJoin('units', 'jobs.unit_id','=','units.id')
			->selectRaw('jobs.*, companies.salesforce_id as salesforce_id, companies.name_english as comp_name_english , units.name as unit_name')
			->whereNotNull('salesforce_id')
			->where('salesforce_id' , '!=', '')
			->orderBy('jobs.company_id')
			->orderBy('jobs.id')
			->get();

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
//			$job_cat_name  = str_replace( "\xc2\xa0", " ", $job->job_cat_name );
			$job_cat_name  = str_replace( "\xc2\xa0", " ", getJobCategoryName() );
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


			$g_url = config('app.url') . "/admin/mypage/job/edit?company_id=" . $job->company_id . "&job_id=" . $job->id;

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


/*******************************************
* 更新CSVファイルアップロード
********************************************/
    public function upload_csv(Request $request) {

		$validatedData = $request->validate([
			'file' => ['required','mimes:csv','extensions:csv'],
		]);

		$file_name = $request->file('file')->getClientOriginalName();
		$request->file('file')->storeAS('public/comp_jobs/csv',$file_name);

		return redirect('admin/mypage/joblist')->with('upload_success', 'アップロード完了しました。');
	}


/*******************************************
* RPA エクセル ファイルアップロード
********************************************/
    public function upload_excel(Request $request) {

		$validatedData = $request->validate([
			'file' => ['required','mimes:xlsx,xls','extensions:xlsx,xls'],
		]);

		$file_name = $request->file('file')->getClientOriginalName();
		$path = $request->file('file')->storeAS('public/comp_jobs/excel',$file_name);

		Storage::move($path, 'public/comp_jobs/' . basename($path));

		return redirect('admin/mypage/joblist')->with('upload_success', 'アップロード完了しました。');
	}


/*************************************
* クチコミ一覧
**************************************/
	public function eval(Request $request)
	{
		if (isset($request->sel_aprove)) {
			$sel_aprove = $request->sel_aprove;
		} else {
			$sel_aprove = '2';
		}

		$query = Evaluation::leftJoin('companies', 'evaluations.company_id', 'companies.id')
			->leftJoin('users', 'evaluations.user_id', 'users.id');

		if ($sel_aprove != '99') {
			$query = $query->where('approve_flag' , $sel_aprove);
		}
			
		if (isset($request->freeword)) {

			$freeword = $request->freeword;

			$query = $query
				->where(function($query) use ($freeword) {
					$query->where('companies.name',     'like', "%{$freeword}%")
					->orWhere('companies.name_kana',    'like', "%{$freeword}%")
					->orWhere('companies.name_english', 'like', "%{$freeword}%")
					;
				});
		}

		$evalList = $query->selectRaw("evaluations.*, companies.name as comp_name, users.name as user_name, users.sex as user_sex")
			->orderBy('approve_flag')
			->orderBy('updated_at' ,'desc')
			->paginate(20);

		return view('admin.mypage_eval' ,
			[
			'evalList'   => $evalList,
			'sel_aprove' => $sel_aprove,
			'freeword'   => $request->input('freeword'),
			]
		);

	}


/*************************************
* クチコミ編集
**************************************/
	public function eval_edit(Request $request)
	{

		if (isset($request->eval_id)) {
			$eval = Evaluation::leftJoin('users', 'evaluations.user_id', 'users.id')
			->selectRaw("evaluations.*, users.id as user_id, users.name as user_name, users.sex as user_sex")
			->where('evaluations.id' ,$request->eval_id)
			->first();

			$comp = Company::find($eval->company_id);

		} else {
			$eval = new Evaluation();

			$eval->approve_flag = '0';
			
			if (isset($request->comp_id)) {
				$comp = Company::find($request->comp_id);
			} else {
				$comp = new Company();
			}
		}

		return view('admin.eval_edit' ,compact(
			'comp',
			'eval',
		));
	}


/*************************************
* クチコミ編集
**************************************/
	public function eval_store(Request $request)
	{
		$validated = $request->validate([
			'comp_id'       => ['required', 'string'],
			'sex'           => ['required'],
			'emp_status'    => ['required'],
			'tenure_status' => ['required'],
			'join_status'   => ['required'],
			'join_year'     => ['required'],
			'retire_year'   => ['required'],
			'occupation'    => ['required', 'string'],
			'approve_flag'  => ['required'],
		]);

		if (!isset($request->eval_id)) {
			$eval = Evaluation::create([
				'company_id'         => $request->comp_id,
				'sex'                => $request->sex,
				'emp_status'         => $request->emp_status,
				'tenure_status'      => $request->tenure_status,
				'join_status'        => $request->join_status,
				'join_year'          => $request->join_year,
				'retire_year'        => $request->retire_year,
				'occupation'         => $request->occupation,
				'ote_income'         => $request->ote_income,
				'salary_point'       => $request->salary_point,
				'salary_content'     => $request->salary_content,
				'welfare_point'      => $request->welfare_point,
				'welfare_content'    => $request->welfare_content,
				'upbring_point'      => $request->upbring_point,
				'upbring_content'    => $request->upbring_content,
				'compliance_point'   => $request->compliance_point,
				'compliance_content' => $request->compliance_content,
				'motivation_point'   => $request->motivation_point,
				'motivation_content' => $request->motivation_content,
				'work_life_point'    => $request->work_life_point,
				'work_life_content'  => $request->work_life_content,
				'remote_point'       => $request->remote_point,
				'remote_content'     => $request->remote_content,
				'retire_point'       => $request->retire_point,
				'retire_content'     => $request->retire_content,
				'approve_flag'       => $request->approve_flag,
				'answer_date'        => $request->answer_date,
			]);

		} else {
			$eval = Evaluation::find($request->eval_id);

			$eval->sex                = $request->sex;
			$eval->emp_status         = $request->emp_status;
			$eval->tenure_status      = $request->tenure_status;
			$eval->join_status        = $request->join_status;
			$eval->join_year          = $request->join_year;
			$eval->retire_year        = $request->retire_year;
			$eval->occupation         = $request->occupation;
			$eval->ote_income         = $request->ote_income;
			$eval->salary_point       = $request->salary_point;
			$eval->salary_content     = $request->salary_content;
			$eval->welfare_point      = $request->welfare_point;
			$eval->welfare_content    = $request->welfare_content;
			$eval->upbring_point      = $request->upbring_point;
			$eval->upbring_content    = $request->upbring_content;
			$eval->compliance_point   = $request->compliance_point;
			$eval->compliance_content = $request->compliance_content;
			$eval->motivation_point   = $request->motivation_point;
			$eval->motivation_content = $request->motivation_content;
			$eval->work_life_point    = $request->work_life_point;
			$eval->work_life_content  = $request->work_life_content;
			$eval->remote_point       = $request->remote_point;
			$eval->remote_content     = $request->remote_content;
			$eval->retire_point       = $request->retire_point;
			$eval->retire_content     = $request->retire_content;
			$eval->approve_flag       = $request->approve_flag;
			$eval->answer_date        = $request->answer_date;

			$eval->save();
		}


		return redirect()->route('admin.mypage.eval.edit', 
			[
			'eval_id' => $eval->id,
			])
			->with('status', 'success-store');
	}



/*************************************
* ジョブ一覧 SFC用
**************************************/
	public function jobsfc(Request $request)
	{
		$comp_name = $request->comp_name;

		$jobList = Job::Join('companies', 'jobs.company_id','=','companies.id')
			->leftJoin('units', 'jobs.unit_id','=','units.id')
			->selectRaw('jobs.*, companies.salesforce_id as salesforce_id, companies.name_english as comp_name_english , units.name as unit_name')
			->whereNotNull('salesforce_id')
			->where('salesforce_id' , '!=', '');

		$words = array();
		if (!empty($comp_name)) {
			$fd = str_replace('　', ' ',$comp_name);
			$words = explode(" ", $fd);

			for ($i = 0; $i < count($words); $i++) {
				$jobList = $jobList
					->where(function($query) use ($words ,$i) {
						$query->where('companies.name' , 'like', '%' . $words[$i] .'%')
						->orWhere('companies.name_english' , 'like', '%' . $words[$i] .'%');
					});

			}
		}

		// ダウンロード
		if (!empty($request->dl)) {
			$jobList = $jobList->orderBy('jobs.company_id')
				->orderBy('jobs.id')
				->get();

			$fileName = "joblist_" . date("Ymd_His") . ".csv";
			$dlFileName = "Gaishi_Joblit.csv";
			$csvFile = $this->CSV_DIR . "/" . $fileName;
			$dlFile = $this->CSV_DIR . "/" . $dlFileName;

			$this->make_sfccsv($csvFile ,$jobList);

			$mimeType = Storage::mimeType($csvFile);
			$headers = [['Content-Type' => $mimeType]];

			Storage::delete($dlFile);
			Storage::copy($csvFile, $dlFile);

			return Storage::download($dlFile, $dlFileName, $headers);

		// 分割 CSV
		} else if (!empty($request->div)) {

			$jobList = $jobList->orderBy('jobs.company_id')
				->orderBy('jobs.id')
				->get();

			$dir = date("Ymd_His");
//			$fileName = "joblist";
			$fileName = date("Ymd");
			
			$dlFileName = "Gaishi_Joblist.zip";

			$csvFile = $this->CSV_DIR . "/" . $fileName . "_" . $dir . ".csv";
			$dlFile  = $this->CSV_DIR . "/" . $dlFileName;

			$files = Storage::files($this->SFC_DIR);
			Storage::delete($files);

			$this->make_sfccsv($csvFile ,$jobList);

			$this->div_csv($csvFile ,$fileName);


			$files = Storage::allFiles($this->SFC_DIR);

			foreach($files as $file) {
            	$filePaths[] = Storage::disk('local')->path($file);
        	}

			Storage::delete($dlFile);

			$localDir = Storage::disk('local')->path($this->CSV_DIR);
			
			Zip::create($dlFileName, $filePaths)
                 ->saveTo($localDir);

			$mimeType = Storage::mimeType($csvFile);
			$headers = [['Content-Type' => $mimeType]];

			return Storage::download($dlFile, $dlFileName, $headers);

		} else {
			$jobList = $jobList->orderBy('jobs.company_id')
				->orderBy('jobs.id')
				->paginate(20);
		}




		$idx = 0;
		foreach ($jobList as $job) {

			// 半角スペース 変換
			$job_name      = !empty($job->name)                 ? str_replace( "\xc2\xa0", " ", $job->name ) : '';
			$job_code      = !empty($job->job_code)             ? str_replace( "\xc2\xa0", " ", $job->job_code ) : '';
			$unit_name     = !empty($job->unit_name)            ? str_replace( "\xc2\xa0", " ", $job->unit_name ) : '';
			$job_cat_name  = !empty($job->getJobCategoryName()) ? str_replace( "\xc2\xa0", " ", $job->getJobCategoryName() ) : '';
			$sub_category  = !empty($job->sub_category)         ? str_replace( "\xc2\xa0", " ", $job->sub_category ) : '';
			$intro         = !empty($job->intro)                ? str_replace( "\xc2\xa0", " ", $job->intro ) : '';
			$working_place = !empty($job->working_place)        ? str_replace( "\xc2\xa0", " ", $job->working_place ) : '';
			$url           = !empty($job->url)                  ? str_replace( "\xc2\xa0", " ", $job->url ) : '';
			$for_agent     = !empty($job->for_agent)            ? str_replace( "\xc2\xa0", " ", $job->for_agent ) : '';

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


			$jobList[$idx]->name = $job_code_hd . $job_name;
			$jobList[$idx]->short_name = $job_code_hd . $job_short_name  . $job_id_ft;
			$jobList[$idx]->job_code = $job_code;
			$jobList[$idx]->unit_name = $unit_name;
			$jobList[$idx]->job_cat_name = $job_cat_name;
			$jobList[$idx]->for_agent = $for_agent;

			$idx++;
		}

		return view('admin.mypage_jobsfc' ,compact(
			'jobList',
			'comp_name',
		));

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
//			$job_cat_name  = str_replace( "\xc2\xa0", " ", $job->job_cat_name );
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
		
			if ($job->open_flag == '1') {
				$open_kbn = 'open';
			} else {
				$open_kbn = mb_convert_encoding('一般Web情報','SJIS-WIN' ,'UTF-8');
			}


			$g_url = config('app.url') . "/admin/mypage/job/edit?company_id=" . $job->company_id . "&job_id=" . $job->id;


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
