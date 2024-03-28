<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Models\Company;
use App\Models\Job;
use App\Models\JobPr;
use App\Models\SearchUserHist;
use App\Models\JobCat;
use App\Models\JobCatDetail;
use App\Models\BusinessCat;
use App\Models\BusinessCatDetail;
use App\Models\ConstLocation;
use App\Models\User;
use App\Models\Interview;
use App\Models\Income;
use App\Models\IndustoryCat;
use App\Models\IndustoryCatDetail;
use App\Models\CommitCat;
use App\Models\CommitCatDetail;
use App\Models\RankingJob;

class JobController extends Controller
{
	public function __construct()
	{
//		$this->middleware('auth:user');
	}


/*************************************
* 初期表示
**************************************/
	public function index()
	{
		
		$loginUser = Auth::guard('user')->user();
		
		if (isset($loginUser->id)) {
			$searchUserHist = SearchUserHist::where('user_id' ,$loginUser->id)->first();
		} else {
			$searchUserHist = new SearchUserHist();
		}

		$jobList = $this->search_all();
	
 		return view('user.job' ,compact(
 			'jobList',
			'searchUserHist',
			));
	}


/*************************************
* 一覧
**************************************/
	public function get_param($param)
	{
		$result = array();
		$id = '';
		$cat = '';
		
		if (false !== strpos($param, 'location')) { // ロケーション
			$id = str_replace('location', '', $param);
			$cat = ConstLocation::find($id);
			$id = explode(',', $id);

		} else if (false !== strpos($param, 'jobcategory')) { // 職種１
			$id = str_replace('jobcategory', '', $param);
			$cat = JobCat::find($id);

		} else if (false !== strpos($param, 'occupation')) { // 職種２
			$id = str_replace('occupation', '', $param);
			$cat = JobCatDetail::find($id);

		} else if (false !== strpos($param, 'indcat')) { // インダストリ1
			$id = str_replace('indcat', '', $param);
			$cat = IndustoryCat::find($id);

		} else if (false !== strpos($param, 'industory')) { // インダストリ2
			$id = str_replace('industory', '', $param);
			$cat = IndustoryCatDetail::find($id);

		} else if (false !== strpos($param, 'buscat')) { // 業種1
			$id = str_replace('buscat', '', $param);
			$cat = BusinessCat::find($id);

		} else if (false !== strpos($param, 'business')) { // 業種2
			$id = str_replace('business', '', $param);
			$cat = BusinessCatDetail::find($id);

		} else if (false !== strpos($param, 'income')) { // 年収
			$id = str_replace('income', '', $param);
			$cat = Income::find($id);
			$id = explode(',', $id);

		} else if (false !== strpos($param, 'commit')) { // こだわり
			$id = str_replace('commit', '', $param);
			$cat = CommitCatDetail::find($id);
		}

		if (!empty($id)) {
			$result['id'] = $id;
			$result['name'] = $cat->name;
		}
		
		return $result;
	}


/*************************************
* 一覧
**************************************/
	public function list(Request $request, $param1 = null, $param2 = null, $param3 = null)
	{
		$param_count = 0;
		$param1_name = '';
		$param2_name = '';
		$param3_name = '';

		if (!empty($param1)) $param_count++;
		if (!empty($param2)) $param_count++;
		if (!empty($param3)) $param_count++;


		$pg = '';
		if (!empty($request->page)) {
			$pg = "?page={$request->page}";
		}
		
		// param1 チェック
		if (!empty($param1)) {
			$result = $this->get_param($param1);

			$param1_name = $result['name'];

			if (false !== strpos($param1, 'location')) { // ロケーション
				$param['locations'] = $result['id'];

			} else if (false !== strpos($param1, 'jobcategory')) { // 職種１
				$param['job_cats'] = $result['id'];

			} else if (false !== strpos($param1, 'occupation')) { // 職種２
				$param['job_cat_details'] = $result['id'];

			} else if (false !== strpos($param1, 'indcat')) { // インダストリ1
				$param['industory_cats'] = $result['id'];

			} else if (false !== strpos($param1, 'industory')) { // インダストリ2
				$param['industory_cat_details'] = $result['id'];

			} else if (false !== strpos($param1, 'buscat')) { // 業種1
				$param['business_cats'] = $result['id'];

			} else if (false !== strpos($param1, 'business')) { // 業種2
				$param['business_cat_details'] = $result['id'];

			} else if (false !== strpos($param1, 'income')) { // 年収
				$param['incomes'] = $result['id'];

			} else if (false !== strpos($param1, 'commit')) { // こだわり
				$param['commit_cat_details'] = $result['id'];
			}
		}

		// param2 チェック
		if (!empty($param2)) {
			$result = $this->get_param($param2);

			$param2_name = $result['name'];

			if (false !== strpos($param2, 'location')) { // ロケーション
				$param['locations'] = $result['id'];

			} else if (false !== strpos($param2, 'jobcategory')) { // 職種１
				$param['job_cats'] = $result['id'];

			} else if (false !== strpos($param2, 'occupation')) { // 職種２
				$param['job_cat_details'] = $result['id'];

			} else if (false !== strpos($param2, 'indcat')) { // インダストリ1
				$param['industory_cats'] = $result['id'];

			} else if (false !== strpos($param2, 'industory')) { // インダストリ2
				$param['industory_cat_details'] = $result['id'];

			} else if (false !== strpos($param2, 'buscat')) { // 業種1
				$param['business_cats'] = $result['id'];

			} else if (false !== strpos($param2, 'business')) { // 業種2
				$param['business_cat_details'] = $result['id'];

			} else if (false !== strpos($param2, 'income')) { // 年収
				$param['incomes'] = $result['id'];

			} else if (false !== strpos($param2, 'commit')) { // こだわり
				$param['commit_cat_details'] = $result['id'];
			}
		}

		// param3 チェック
		if (!empty($param3)) {
			$result = $this->get_param($param3);

			$param3_name = $result['name'];

			if (false !== strpos($param3, 'location')) { // ロケーション
				$param['locations'] = $result['id'];

			} else if (false !== strpos($param3, 'jobcategory')) { // 職種１
				$param['job_cats'] = $result['id'];

			} else if (false !== strpos($param3, 'occupation')) { // 職種２
				$param['job_cat_details'] = $result['id'];

			} else if (false !== strpos($param3, 'indcat')) { // インダストリ1
				$param['industory_cats'] = $result['id'];

			} else if (false !== strpos($param3, 'industory')) { // インダストリ2
				$param['industory_cat_details'] = $result['id'];

			} else if (false !== strpos($param3, 'buscat')) { // 業種1
				$param['business_cats'] = $result['id'];

			} else if (false !== strpos($param3, 'business')) { // 業種2
				$param['business_cat_details'] = $result['id'];

			} else if (false !== strpos($param3, 'income')) { // 年収
				$param['incomes'] = $result['id'];

			} else if (false !== strpos($param3, 'commit')) { // こだわり
				$param['commit_cat_details'] = $result['id'];
			}
		}


		$param['freeword'] = $request->freeword;
		$param['comps']    = $request->comps;

		// ロケーション　配列 -> カンマ区切り
		if ( empty($param['locations']) && !empty($request->locations) ) {
			$param['locations'] = $request->locations;
		}

		if ( empty($param['job_cats']) && !empty($request->job_cats) ) {
			$param['job_cats'] = $request->job_cats;
		}

		if ( empty($param['job_cat_details']) && !empty($request->job_cat_details) ) {
			$param['job_cat_details'] = $request->job_cat_details;
		}

		if ( empty($param['industory_cats']) && !empty($request->industory_cats) ) {
			$param['industory_cats'] = $request->industory_cats;
		}

		if ( empty($param['industory_cat_details']) && !empty($request->industory_cat_details) ) {
			$param['industory_cat_details'] = $request->industory_cat_details;
		}

		if ( empty($param['business_cats']) && !empty($request->business_cats) ) {
			$param['business_cats'] = $request->business_cats;
		}

		if ( empty($param['business_cat_details']) && !empty($request->business_cat_details) ) {
			$param['business_cat_details'] = $request->business_cat_details;
		}

		// 年収　配列 -> カンマ区切り
		if (empty($param['incomes']) && !empty($request->incomes)) {
			$param['incomes'] = $request->incomes;
		}

		if ( empty($param['commit_cat_details']) && !empty($request->commit_cat_details) ) {
			$param['commit_cat_details'] = $request->commit_cat_details;
		}


		// noindex 設定
		if ($param_count == 1) {
			$noindex = '';

		} else if($param_count == 2) {
			if ( (false !== strpos($param1, 'location')) || (false !== strpos($param1, 'jobcategory')) || (false !== strpos($param1, 'occupation')) ) { 
				$noindex = '';
			} else {
				$noindex = '1';
			}

		} else if ($param_count == 3) {
			if ( (false !== strpos($param1, 'location')) && ( (false !== strpos($param2, 'jobcategory')) || (false !== strpos($param2, 'occupation')) )) { 
				$noindex = '';
			} else {
				$noindex = '1';
			}

		} else {
			$noindex = '1';
		}

		if ( (false !== strpos($param2, 'commit')) || (false !== strpos($param3, 'commit')) ) {
			$data = CommitCatDetail::find($param['commit_cat_details']);

			if ($data->index_flag == '1') {
				$noindex = '';
			} else {
				$noindex = '1';
			}
		}

		// canonical 設定
		if ($noindex == '1') {
			if (!empty($param['locations'])) {
				$id = $param['locations'][0];
				$canonical = url('job/list') . "/location{$id}";

			} else if (!empty($param['job_cats']) ) { // 職種１
				$id = explode(',', $param['job_cats']);
				$canonical = url('job/list') . "/jobcategory{$id[0]}";

			} else if  (!empty($param['job_cat_details']) ) { // 職種２
				$id = explode(',', $param['job_cat_details']);
				$canonical = url('job/list') . "/occupation{$id[0]}";

			} else if  (!empty($param['industory_cats']) ) { // インダストリ1
				$id = explode(',', $param['industory_cats']);
				$canonical =url('job/list') . "/indcat{$id[0]}";

			} else if  (!empty($param['industory_cat_details']) ) { // インダストリ2
				$id = explode(',', $param['industory_cat_details']);
				$canonical = url('job/list') . "/industory{$id[0]}";

			} else if  (!empty($param['business_cats']) ) { // 業種1
				$id = explode(',', $param['business_cats']);
				$canonical = url('job/list') . "/buscat1{$id[0]}";

			} else if  (!empty($param['business_cat_details']) ) { // 業種2
				$id = explode(',', $param['business_cat_details']);
				$canonical = url('job/list') . "/business{$id[0]}";

			} else if  (!empty($param['locations']) ) { // ロケーション
				$canonical = url('job/list') . "/location1{$param['locations'][0]}";

			} else if  (!empty($param['commit_cat_details']) ) { // こだわり
				$id = explode(',', $param['commit_cat_details']);
				$canonical = url('job/list') . "/commit{$id[0]}";

			} else if  (!empty($param['incomes']) ) { // 年収
				$canonical = url('job/list') . "/income{$param['incomes'][0]}";

			} else {
				$canonical = url()->current();
			}

		} else {
			$canonical = url()->current();
		}

			//page があれば ?pageを付加
			$canonical = $canonical . $pg;

		// 条件保存
		if ($request->save_flag == '1') {
			$loginUser = Auth::guard('user')->user();

			$searchUserHist = SearchUserHist::where('user_id' ,$loginUser->id)->first();

			if (empty($searchUserHist)) {
				$searchUserHist = new SearchUserHist();
				$searchUserHist->user_id  = $loginUser->id;
			}

			$searchUserHist->freeword              = $request->freeword;
			$searchUserHist->comps                 = $request->comps;
			$searchUserHist->job_cats              = $request->job_cats;
			$searchUserHist->job_cat_details       = $request->job_cat_details;
			$searchUserHist->business_cats         = $request->business_cats;
			$searchUserHist->business_cat_details  = $request->business_cat_details;
			$searchUserHist->industory_cats        = $request->industory_cats;
			$searchUserHist->industory_cat_details = $request->industory_cat_details;
			$searchUserHist->commit_cat_details    = $request->commit_cat_details;

			if (!empty($request->locations)) {
				$searchUserHist->locations  = implode(',', $request->locations);
			} else {
				$searchUserHist->locations  = null;
			}
			if (!empty($request->incomes)) {
				$searchUserHist->incomes = implode(',', $request->incomes);
			} else {
				$searchUserHist->incomes = null;
			}

			$searchUserHist->save();

			return redirect('/job');
		}

		$jobList = $this->search_local($param);

		// ページング対応
		$jobList = $jobList->appends($request->input());

//dd($param);

 		return view('user.job_list' ,compact(
 			'jobList',
 			'canonical',
 			'noindex',
 			'param',
 			'param1',
 			'param2',
 			'param3',
 			'param1_name',
 			'param2_name',
 			'param3_name',
 			'param_count',
			));
	}


/*************************************
* 企業ジョブ一覧
**************************************/
	public function comp_job_list(Request $request, $comp_id)
	{
		$param['comps'] = $comp_id;

		$jobList = $this->search_local($param);

		$comp = Company::where('id' , $comp_id)
			->first();

		// ページング対応
		$jobList = $jobList->appends($request->input());

 		return view('user.comp_job_list' ,compact(
 			'comp',
 			'jobList',
			));
	}


/*************************************
* 検索条件取得
**************************************/
	public function search_all()
	{
		$jobList = Job::join('ranking_jobs','jobs.id', 'ranking_jobs.job_id')
			->join('companies','jobs.company_id', 'companies.id')
			->leftJoin('rankings', 'rankings.company_id', 'jobs.company_id')
			->where('companies.open_flag' ,'1')
			->where('jobs.open_flag','1')
			->whereNotNull('jobs.intro')
			->where('jobs.intro','!=','');


		$jobList = $jobList->selectRaw('jobs.*,' .
					  'companies.name as company_name ,companies.logo_file as logo_file ,companies.image_file as image_file,' .
					  'rankings.* ')
			->orderBy('rankings.total_point','DESC')
			->orderBy('jobs.updated_at','DESC')
			->paginate(10);

		return $jobList;
	}


/*************************************
* 検索条件取得
**************************************/
	public function search_local($param)
	{

		$jobList = Job::Join('companies','jobs.company_id', 'companies.id')
			->leftJoin('rankings', 'rankings.company_id', 'jobs.company_id')
			->where('companies.open_flag' ,'1')
			->where('jobs.open_flag','1')
			->whereNotNull('jobs.intro')
			->where('jobs.intro','!=','');

		// ロケーション
		if (!empty($param['locations'])) {
			$loc = $param['locations'];

			$jobList = $jobList->where(function($query) use ($loc) {
			    $query->where('jobs.locations' ,'like', '%' . $loc[0] .'%');
			    
				for ($i = 1; $i < count($loc); $i++) {
					$query= $query->orWhere('jobs.locations' ,'like', '%' . $loc[$i] .'%');
				}
			});
		}

		// 年収
		if ( !empty($param['incomes']) ) {
			$inc = $param['incomes'];
		    $jobList = $jobList->whereIn('jobs.income_id' , $inc);
		}

		if ( isset($param['comps']) ) {
			$comp = explode(",", $param['comps']);
			$jobList = $jobList->whereIn('jobs.company_id' ,$comp);
		}

		// 職種
		if ( isset($param['job_cats']) && isset($param['job_cat_details']) ) {
			$cat = explode(",", $param['job_cats']);
			$detail = explode(",", $param['job_cat_details']);

			$jobList = $jobList->where(function($query) use ($cat, $detail) {
			    $query->where('jobs.job_cats' ,'like', '%[' . $cat[0] .']%');
			    
				for ($i = 1; $i < count($cat); $i++) {
					$query= $query->orWhere('jobs.job_cats' ,'like', '%[' . $cat[$i] .']%');
				}

				for ($i = 0; $i < count($detail); $i++) {
					$query= $query->orWhere('jobs.job_cat_details' ,'like', '%[' . $detail[$i] .']%');
				}
			});

		} else if ( isset($param['job_cats']) ) {
			$cat = explode(",", $param['job_cats']);

			$jobList = $jobList->where(function($query) use ($cat) {
			    $query->where('jobs.job_cats' ,'like', '%[' . $cat[0] .']%');
			    
				for ($i = 1; $i < count($cat); $i++) {
					$query= $query->orWhere('jobs.job_cats' ,'like', '%[' . $cat[$i] .']%');
				}
			});

		} else if ( isset($param['job_cat_details']) ) {
			$detail = explode(",", $param['job_cat_details']);

			$jobList = $jobList->where(function($query) use ($detail) {
			    $query->where('jobs.job_cat_details' ,'like', '%[' . $detail[0] .']%');
			    
				for ($i = 1; $i < count($detail); $i++) {
					$query= $query->orWhere('jobs.job_cat_details' ,'like', '%[' . $detail[$i] .']%');
				}
			});
		}

		// インダストリ
		if ( isset($param['industory_cats']) && isset($param['industory_cat_details']) ) {
			$cat = explode(",", $param['industory_cats']);
			$detail = explode(",", $param['industory_cat_details']);

			$jobList = $jobList->where(function($query) use ($cat, $detail) {
			    $query->where('jobs.industory_cats' ,'like', '%[' . $cat[0] .']%');
			    
				for ($i = 1; $i < count($cat); $i++) {
					$query= $query->orWhere('jobs.industory_cats' ,'like', '%[' . $cat[$i] .']%');
				}

				for ($i = 0; $i < count($detail); $i++) {
					$query= $query->orWhere('jobs.industory_cat_details' ,'like', '%[' . $detail[$i] .']%');
				}
			});

		} else if ( isset($param['industory_cats']) ) {
			$cat = explode(",", $param['industory_cats']);

			$jobList = $jobList->where(function($query) use ($cat) {
			    $query->where('jobs.industory_cats' ,'like', '%[' . $cat[0] .']%');
			    
				for ($i = 1; $i < count($cat); $i++) {
					$query= $query->orWhere('jobs.industory_cats' ,'like', '%[' . $cat[$i] .']%');
				}
			});

		} else if ( isset($param['industory_cat_details']) ) {
			$cat = explode(",", $param['industory_cat_details']);

			$jobList = $jobList->where(function($query) use ($cat) {
			    $query->where('jobs.industory_cat_details' ,'like', '%[' . $cat[0] .']%');
			    
				for ($i = 1; $i < count($cat); $i++) {
					$query= $query->orWhere('jobs.industory_cat_details' ,'like', '%[' . $cat[$i] .']%');
				}
			});
		}


		// 業種
		if ( isset($param['business_cats']) && isset($param['business_cat_details']) ) {
			$cat = explode(",", $param['business_cats']);
			$job = explode(",", $param['business_cat_details']);

			$jobList = $jobList->where(function($query) use ($cat, $detail) {
				$query->where('companies.business_cats' ,'like', '%[' . $cat[0] .']%');
			    
				for ($i = 1; $i < count($cat); $i++) {
					$query= $query->orWhere('companies.business_cats' ,'like', '%[' . $cat[$i] .']%');
				}

				for ($i = 0; $i < count($detail); $i++) {
					$query= $query->orWhere('companies.business_cat_details' ,'like', '%[' . $detail[$i] .']%');
				}
			});

		} else if ( isset($param['business_cats']) ) {
			$cat = explode(",", $param['business_cats']);

			$jobList = $jobList->where(function($query) use ($cat) {
				$query->where('companies.business_cats' ,'like', '%[' . $cat[0] .']%');
			    
				for ($i = 1; $i < count($cat); $i++) {
					$query= $query->orWhere('companies.business_cats' ,'like', '%[' . $cat[$i] .']%');
				}
			});

		} else if ( isset($param['business_cat_details']) ) {
			$detail = explode(",", $param['business_cat_details']);

			$jobList = $jobList->where(function($query) use ($detail) {
				$query->where('companies.business_cat_details' ,'like', '%[' . $detail[0] .']%');
			    
				for ($i = 1; $i < count($detail); $i++) {
					$query= $query->orWhere('companies.business_cat_details' ,'like', '%[' . $detail[$i] .']%');
				}
			});
		}


		// こだわり
		if ( !empty($param['commit_cat_details']) ) {
			$cat = explode(",", $param['commit_cat_details']);

			$jobList = $jobList->where(function($query) use ($cat) {
				$query->where('companies.commit_cats' ,'like', '%[' . $cat[0] .']%');
			    
				for ($i = 1; $i < count($cat); $i++) {
					$query= $query->orWhere('companies.commit_cats' ,'like', '%[' . $cat[$i] .']%');
				}
			});
		}

		// フリーワード
		if ( isset($param['freeword']) ) {
			$freeword = str_replace('　', ' ', $param['freeword']);
			$words = explode(" ", $freeword);

			for ($i = 0; $i < count($words); $i++) {
				$jobList = $jobList
					->where(function($query) use ($words ,$i) {
						$query->where('companies.name' , 'like', "%{$words[$i]}%")
						->orWhere('jobs.name' , 'like', "%{$words[$i]}%")
						->orWhere('jobs.intro' , 'like', "%{$words[$i]}%")
						->orWhere('jobs.job_code' , 'like', "%{$words[$i]}%")
						->orWhere('jobs.sub_category' , 'like', "%{$words[$i]}%")
						->orWhere('jobs.working_place' , 'like', "%{$words[$i]}%")
						;
					});
			}
		}

		$jobList = $jobList->selectRaw('jobs.*,' .
					  'companies.name as company_name ,companies.logo_file as logo_file ,companies.image_file as image_file, companies.commit_cats as commit_cats,' .
					  'rankings.* ')
			->paginate(10);

		return $jobList;
	}
	

/*************************************
* 詳細
**************************************/
	public function detail(Request $request, $compId, $jobId)
	{
		$loginUser = Auth::guard('user')->user();
		
		if (isset($loginUser->id)) {
			$user = User::find($loginUser->id);
			$favorite = explode(",", $user->favorite_job);
			$cnt = count($favorite);
		} else {
			$cnt = 0;
		}

		$favorite_on = 0;
		for ($i = 0; $i < $cnt; $i++) {
			if ($favorite[$i] == $jobId) {
				$favorite_on = 1;
				break;
			}
		}

		$job = Job::where('jobs.id' ,$jobId)
			->first();

		if (empty($job)) {
 			return view('user.not_ready');
		}

		if (isset($loginUser->id)) {
			$interview1 = Interview::where('user_id' ,$loginUser->id)
				->where('job_id' ,$job->id)
				->where('interview_type' ,'0')
				->where('interview_kind' ,'2')
				->orderBy('created_at' ,'desc')
				->first();

			$interview2 = Interview::where('user_id' ,$loginUser->id)
				->where('job_id' ,$job->id)
				->where('interview_type' ,'1')
				->where('interview_kind' ,'2')
				->orderBy('created_at' ,'desc')
				->first();
		}

		$interviewList = array();
		if(isset($interview1)) $interviewList[] = $interview1;
		if(isset($interview2)) $interviewList[] = $interview2;

		$comp = Company::find($job->company_id);

 		return view('user.job_detail' ,compact(
 			'comp',
 			'job',
 			'interviewList',
 			'favorite_on',
 			));
	}


/*************************************
* 初期表示
**************************************/
	public function job_activity()
	{
		
		$loginUser = Auth::guard('user')->user();

		$searchUserHist = SearchUserHist::where('user_id' ,$loginUser->id)->first();

		if (!$searchUserHist) {
			$searchUserHist = SearchUserHist::create([
				'user_id'  => $loginUser->id,
			]);
		}

		$locList = array();
		if (!empty($searchUserHist->locations)) {
			$loc = explode(",", $searchUserHist->locations);
			$locList = ConstLocation::whereIn('id' ,$loc )->get();
		}
		
		$compList = array();
		if (!empty($searchUserHist->comps)) {
			$comp = explode(",", $searchUserHist->comps);
			$compList = Company::whereIn('id' ,$comp )->where('companies.open_flag' ,'1')->get();
		}
/*		
		$jobCatList = array();
		if (!empty($searchUserHist->job_cats)) {
			$job_cat = explode(",", $searchUserHist->job_cats);
			$jobCatList = JobCat::whereIn('id' ,$job_cat  )->get();
		}
*/		
		$jobCatList = array();
		if (!empty($searchUserHist->job_cats)) {
			$job_cat_detail = explode(",", $searchUserHist->job_cat_details);
			$jobCatList = JobCatDetail::whereIn('id' ,$job_cat_detail  )->get();
		}
		
		$busCatList = array();
		if (!empty($searchUserHist->business_cats)) {
			$bus_cat = explode(",", $searchUserHist->business_cats);
			$busCatList = BusinessCatDetail::whereIn('id' ,$bus_cat )->get();
		}

		$incList = array();
		if (!empty($searchUserHist->incomes)) {
			$inc = explode(",", $searchUserHist->incomes);
			$incList = Income::whereIn('id' ,$inc )->get();
		}

		$all_list = array();
		
		foreach ($locList as $loc) {
			$all_list[] = $loc->name;
		}

		foreach ($compList as $comp) {
			$all_list[] = $comp->name;
		}

		foreach ($jobCatList as $job) {
			$all_list[] = $job->name;
		}

		foreach ($busCatList as $bus) {
			$all_list[] = $bus->name;
		}

		$freeword = str_replace('　', ' ', $searchUserHist->freeword);
		$words = explode(" ", $freeword);

		if (!empty($words[0])) {
			foreach ($words as $wd) {
				$all_list[] = $wd;
			}
		}

		$job_act = array(
			'all_list'    => $all_list,
		);

		return $job_act;

	}



/*************************************
* お気に入り
**************************************/
	public function favorite()
	{
		
		$loginUser = Auth::guard('user')->user();
		
		$user = User::find($loginUser->id);

		$favorite = explode(",", $user->favorite_job);
		$jobList = Job::Join('companies','jobs.company_id','=','companies.id')
			->selectRaw('jobs.*, companies.name as company_name ,companies.logo_file as logo_file ,companies.image_file as image_file')
			->where('companies.open_flag' ,'1')
			->where('jobs.open_flag','1')
			->whereIn('jobs.id' ,$favorite)
			->whereNotNull('jobs.intro')
			->where('jobs.intro','!=','')
			->orderBy('jobs.updated_at' ,'desc')
			->paginate(10);

 		return view('user.job_favorite' ,compact(
 			'jobList',
			));
	}


/*************************************
* お気に入り
**************************************/
	public function favoriteAdd(Request $request)
	{
		
		$loginUser = Auth::guard('user')->user();
		
		$user = User::find($loginUser->id);

		$exist = 0;
		if ($request->job_add == '1') {
			$favorite = explode(",", $user->favorite_job);
			$cnt = count($favorite);

			for ($i = 0; $i < $cnt; $i++) {
				if ($favorite[$i] == $request->job_id) {
					$exist = 1;
					break;
				}
			}

			if ($exist == 0) {
				$favorite[$cnt] = $request->job_id;
			}

		} else {
			$temp = explode(",", $user->favorite_job);
			$cnt = count($temp);

			for ($i = 0; $i < $cnt; $i++) {
				if ($temp[$i] != $request->job_id) {
					$favorite[] = $temp[$i];
				}
			}
		}

		$user->favorite_job = implode(',', $favorite);
		$user->save();

		return redirect("/company/{$request->comp_id}/{$request->job_id}");
	}


}
