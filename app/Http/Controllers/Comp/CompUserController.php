<?php

namespace App\Http\Controllers\Comp;

use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

use Illuminate\Http\Request;

use App\Models\User;
use App\Models\JobCat;
use App\Models\JobCatDetail;
use App\Models\BusinessCatDetail;
use App\Models\Interview;
use App\Models\SearchHist;
use App\Models\UserCompMemo;
use App\Models\ConstLocation;
use App\Models\Company;
use App\Models\CompMember;
use App\Models\CompInmail;

class CompUserController extends UserController
{

	public function __construct()
	{
 		$this->middleware('auth:comp');
	}


/*************************************
* 一覧 候補者検索
**************************************/
	public function index()
	{
		$loginUser = Auth::user();
		
		$searchHist = SearchHist::where('owner_id' ,$loginUser->id)
			->where('use_page' ,'COMP_USER')
			->first();
			
		if (!$searchHist) {
			$searchHist = SearchHist::create([
				'owner_id' => $loginUser->id,
				'use_page' => 'COMP_USER',
			]);
		}

		$search = $searchHist->toArray();

		$userList = $this->search_list($search);

		return view('comp.user_list' ,compact(
			'userList',
			'search',
		));
 

	}


/*************************************
* 一覧 候補者検索
**************************************/
	public function list(Request $request)
	{
		$loginUser = Auth::user();
		
		$searchHist = SearchHist::where('owner_id' ,$loginUser->id)
			->where('use_page' ,'COMP_USER')
			->first();

		$searchHist['from_age'] = $request->from_age;
		$searchHist['to_age'] = $request->to_age;
		$searchHist['current_job'] = $request->current_job;
		$searchHist['location'] = $request->location;
		$searchHist['request_cat'] = $request->request_cat;
		$searchHist['freeword'] = $request->freeword;

		$searchHist->save();

		$search = $searchHist->toArray();

		$userList = $this->search_list($search);

	
		return view('comp.user_list' ,compact(
			'userList',
			'search',
		));
	}


/*************************************
* 検索リスト
**************************************/
	public function search_list($param)
	{

		$loginUser = Auth::user();

		$subSQL0 = \DB::table('users')
			->selectRaw("id, TIMESTAMPDIFF(YEAR, users.birthday, CURDATE()) AS age");
		
		$userQuery = \DB::table('users')
			->JoinSub($subSQL0 , 'user_age' ,'user_age.id', 'users.id')
//			->leftJoin('const_locations', 'users.request_location','=','const_locations.id')
//			->selectRaw("users.*, age ,const_locations.name as location_name")
			->selectRaw("users.*, age")
//			->where('aprove_flag' ,'1')
			->where(function($query) use  ($loginUser) {
				$query->whereNull('users.no_company')
				->orWhere('users.no_company','not LIKE' , "%{$loginUser->company_id}%");
			});

		if (!empty($param['from_age'])) $userQuery = $userQuery->where('age' ,'>=',  $param['from_age']);
		if (!empty($param['to_age'])) $userQuery = $userQuery->where('age' ,'<',  $param['to_age'] + 10);
//		if (!empty($param['current_job'])) $userQuery = $userQuery->whereIn('users.current_job' ,  $param['current_job'] );
		if (!empty($param['location'])) $userQuery = $userQuery->where('users.request_location' , $param['location'] );
		if (!empty($param['request_cat'])) $userQuery = $userQuery->where('users.job_cats' , $param['request_cat'] );

		if (!empty($param['freeword'])) {
			$userQuery = $userQuery
				->where(function($query) use  ($loginUser ,$param) {
					$query->where('users.graduation',  'like', "%{$param['freeword']}%")
					->orWhere('users.company',         'like', "%{$param['freeword']}%")
					->orWhere('users.old_company',     'like', "%{$param['freeword']}%")
					->orWhere('users.job_title',       'like', "%{$param['freeword']}%")
					->orWhere('users.job_content',     'like', "%{$param['freeword']}%")
					->orWhere('users.request_carrier', 'like', "%{$param['freeword']}%")
					->orWhere('users.job_detail',      'like', "%{$param['freeword']}%")
					;
				});
		}

		$userList = $userQuery->orderBy('users.created_at' ,'desc')->paginate(10);
	
		// 1年以内にメッセージのやり取りがあれば氏名も表示
		$pre_date = date("Y-m-d",strtotime("-1 year"));

		$idx = 0;
		foreach ($userList as $user) {

			$catName = array();
			$cats = explode(",", $user->job_cats);
			$len = count($cats);

			for ($i = 0; $i < $len; $i++) {
				$cat = JobCat::find($cats[$i]);
				if ($cat) {
					$catName[] = $cat->name;
				}
			}

			$userList[$idx]->cat_names = join("/",$catName);

			$int_count = Interview::where('interviews.user_id', $user->id)
				->where('aprove_flag', '1')
				->where('updated_at', '>' , $pre_date)
				->count();

			$userList[$idx]->open_flag = '0';
			if ($int_count > 0) $userList[$idx]->open_flag = '1';


			if (!empty($user->request_location)) {
				$loc = explode(',', $user->request_location);
				$ln = ConstLocation::whereIn('id' ,$loc)->get();

				$loc_name = array();
				for ($i = 0; $i < count($ln); $i++) {
					$loc_name[] = $ln[$i]['name'];
				}

				$userList[$idx++]->location_name = implode('/', $loc_name);
			} else {
				$userList[$idx++]->location_name = '';
			}
		}

		return $userList;
	}
	


/*************************************
* ユーザ基本情報 PDF出力
**************************************/
	public function userBasePdf(Request $request)
	{
		$userInfo = "";
		if ( !empty($request->user_id) ) {
			$userInfo = $this->get_user($request->user_id);
		}
		
		$pdf = \PDF::loadView('pdf_templates.user_base',
			['userInfo' => $userInfo],
		);
		$pdf->setPaper('A4');
		
		return $pdf->download('user_basic.pdf');
	}


/*************************************
* ユーザ職務経歴書 PDF出力
**************************************/
	public function userCvPdf(Request $request)
	{
		
		$userInfo = "";
		if ( !empty($request->user_id) ) {
			$userInfo = $this->get_user($request->user_id);
		}
		
	
		$pdf = \PDF::loadView('pdf_templates.user_cv',
			['userInfo' => $userInfo],
		);
		$pdf->setPaper('A4');
		
		return $pdf->download('user_cv.pdf');
	}


/*************************************
* ユーザ職務経歴書(英文) PDF出力
**************************************/
	public function userCvEngPdf(Request $request)
	{
		
		$userInfo = "";
		if ( !empty($request->user_id) ) {
			$userInfo = $this->get_user($request->user_id);
		}
		

		$pdf = \PDF::loadView('pdf_templates.user_cv_eng',
			['userInfo' => $userInfo],
		);
		$pdf->setPaper('A4');
		
		return $pdf->download('user_cv_english.pdf');
	}


/*************************************
* ユーザ履歴書 PDF出力
**************************************/
	public function userVitaePdf(Request $request)
	{
		
		$userInfo = "";
		if ( !empty($request->user_id) ) {
			$userInfo = $this->get_user($request->user_id);
		}

		$pdf = \PDF::loadView('pdf_templates.user_vitae',
			['userInfo' => $userInfo],
		);
		$pdf->setPaper('A4');
		
		return $pdf->download('user_vitae.pdf');
	}


/*************************************
* ユーザ情報取得
**************************************/
	public function get_user($user_id)
	{
		$userInfo = User::leftJoin('const_englishes as eng','users.english','=','eng.id')
			->leftJoin('const_englishes as jpn','users.japanese','=','jpn.id')
			->leftJoin('const_prefs','users.pref' ,'=' ,'const_prefs.id')
			->selectRaw('users.* ,eng.name as english_name ,jpn.name as japanese_name  ,const_prefs.name as pref_name ')
			->where('users.id' ,$user_id)
			->first();

		// 1年以内にメッセージのやり取りがあれば氏名も表示
		$pre_date = date("Y-m-d",strtotime("-1 year"));
	
		$int_count = Interview::where('interviews.user_id', $user_id)
			->where('aprove_flag', '1')
			->where('updated_at', '>' , $pre_date)
			->count();

		$userInfo['open_flag'] = '0';
		if ($int_count > 0) $userInfo['open_flag'] = '1';


		// 勤務地の取得
		$locName = array();
		$locs = explode(",", $userInfo->request_location);
		$locList = ConstLocation::select('name')->whereIn('id' ,$locs)->get();
		foreach ($locList as $loc) {
			$locName[] = $loc->name;
		}
		$userInfo['location_name'] = join(" / " ,$locName);


		// 職種カテゴリ名の取得
		$catName = array();
		$cats = explode(",", $userInfo->job_cats);
		$catList = JobCat::select('name')->whereIn('id' ,$cats)->get();
		foreach ($catList as $cat) {
			$catName[] = $cat->name;
		}
		$userInfo['jobcat_name'] = join(" / ",$catName);

		// 職種名の取得
		$catDetailName = array();
		$cat_details = explode(",", $userInfo->job_cat_details);
		$catDetailList = JobCatDetail::select('name')->whereIn('id' ,$cat_details)->get();
		foreach ($catDetailList as $cat) {
			$catDetailName[] = $cat->name;
		}
		$userInfo['jobcat_detail_name'] = join(" / ",$catDetailName);


		// 業種名の取得
		$catName = array();
		$cats = explode(",", $userInfo->business_cats);
		$catList = BusinessCatDetail::select('name')->whereIn('id' ,$cats)->get();
		foreach ($catList as $cat) {
			$catName[] = $cat->name;
		}
		$userInfo['buscat_name'] = join(" / ",$catName);

		return $userInfo;
	}


/*************************************
* 候補者詳細情報
**************************************/
	public function detail(Request $request)
	{
		$loginUser = Auth::user();
//ddd($request);

		if ( session()->has('user_id') ) {
			$user_id = session('user_id');
			session()->forget('user_id');
		} else {
			$user_id = $request->input('user_id');
		}

		if ( session()->has('memo_open') ) {
			$memo_open = '1';
			session()->forget('memo_open');
		} else {
			$memo_open = '';
		}

		$userDetail = User::find($user_id);

		$comp = Company::find($loginUser->company_id);

		$userComp = Interview::Join('companies','interviews.company_id','=','companies.id')
			->Join('const_results' ,'interviews.result_id' ,'=' , 'const_results.id')
			->selectRaw('const_results.name as result_name')
			->where('interviews.company_id' ,$loginUser->company_id)
			->where('interviews.user_id' ,$user_id)
			->where('interviews.interview_type' ,'1')
			->orderBy('interviews.result_id')
			->first();

		$userCompMemo = UserCompMemo::where('user_id' ,$user_id)
			->leftJoin('comp_members', 'user_comp_memos.member_id' ,'=' ,'comp_members.id')
			->selectRaw("user_comp_memos.* ,comp_members.name as member_name")
			->where('user_comp_memos.company_id' ,$loginUser->company_id)
			->get();


		$interviewList = Interview::leftJoin('const_stages' ,'interviews.stage_id' ,'=', 'const_stages.id')
			->leftJoin('const_statuses' ,'interviews.status_id' ,'=' , 'const_statuses.id')
			->leftJoin('const_results' ,'interviews.result_id' ,'=' , 'const_results.id')
			->leftJoin('comp_members', 'interviews.member_id','=','comp_members.id')
			->leftJoin('companies','interviews.company_id','=','companies.id')
			->leftJoin('units','interviews.unit_id','=','units.id')
			->leftJoin('jobs','interviews.job_id','=','jobs.id')
			->leftJoin('events','interviews.event_id','=','events.id')
			->selectRaw('interviews.*,' .
						'comp_members.name as member_name,' .
						'jobs.name as job_name, jobs.person as job_person,' .
						'const_stages.name as stage_name ,const_statuses.name as status_name, const_results.name as result_name,' .
						'units.name as unit_name, units.person as unit_person,' .
						'companies.name as company_name, companies.person as company_person,' .
						'events.name as event_name, events.person as event_person'
						)
			->where('interviews.company_id' ,$loginUser->company_id)
			->where('interviews.user_id', $user_id)
			->orderBy('updated_at' ,'desc')
			->get();


		$idx = 0;
		foreach ($interviewList as $list) {
			
			$loc = array();
			if ($list->interview_type == '0' && $list->interview_kind == '0') {
				if ( !empty($list->company_person) ) $loc = explode(',', $list->company_person);

			} elseif ($list->interview_type == '0' && $list->interview_kind == '1') {
				if ( !empty($list->unit_person) ) $loc = explode(',', $list->unit_person);

			} elseif ( ($list->interview_type == '0' && $list->interview_kind == '2') || $list->interview_type == '1' ) {
				if ( !empty($list->job_person) ) $loc = explode(',', $list->job_person);

			} elseif ($list->interview_type == '2') {
				if ( !empty($list->event_person) ) $loc = explode(',', $list->event_person);

			};
		
			if ( !empty($loc) ) {
				$ln = CompMember::whereIn('id' ,$loc)->get();

				$person_name = array();
				for ($i = 0; $i < count($ln); $i++) {
					$person_name[] = $ln[$i]['name'];
				}

				$interviewList[$idx++]->person_name = implode('/', $person_name);
			} else {
				$interviewList[$idx++]->person_name = '';
			}
		}



		$userInfo = $this->get_user($user_id);

		$parent_id = $request->parent_id;

		$yearMon = date("Y-m-01");

		$inmail = CompInmail::where('company_id' , $comp->id)
			->where('year_month' ,$yearMon)
			->first();

		if (empty($inmail)) {
			$inmail = CompInmail::create([
				'company_id' => $comp->id,
				'year_month' => $yearMon,
				'inmail_formal' => 0,
				'inmail_casual' => 0,
			]);
		}


		return view('comp.user_detail' ,compact(
			'userDetail',
			'userComp',
			'userCompMemo',
			'memo_open',
			'interviewList',
			'userInfo',
			'parent_id',
			'comp',
			'inmail',
		));
	}


/*************************************
* 候補者詳細情報
**************************************/
	public function memo(Request $request)
	{
		$validator = Validator::make($request->all(), [
    		'content' => ['required', 'string'],
		]);
			
		$userId = $request->user_id;
		
		// 失敗したら
		if ($validator->fails()) {
			return redirect()->back()
				->withErrors($validator)
				->with('user_id' ,$userId);
		}

//ddd($request);
		$loginUser = Auth::user();

		$userCompMemo = UserCompMemo::create([
			'user_id'    => $request->user_id,
			'company_id' => $loginUser->company_id,
			'content' => $request->content,
		]);


		return redirect('comp/user/detail')->with('user_id' ,$userId)->with('memo_open' ,'1');
	}



/*************************************
* 一覧 候補者管理
**************************************/
	public function canIndex()
	{
		$loginUser = Auth::user();
		
		$searchHist = SearchHist::where('owner_id' ,$loginUser->id)
			->where('use_page' ,'COMP_CAND')
			->first();
			
		if (!$searchHist) {
			$searchHist = SearchHist::create([
				'owner_id'        => $loginUser->id,
				'use_page'       => 'COMP_CAND',
			]);
		}
		
		$search = $searchHist->toArray();

		$userList = $this->search_can_list($search);

		return view('comp.candidate_list' ,compact(
			'userList',
			'search',
		));
 
	}


/*************************************
* 一覧 候補者管理
**************************************/
	public function canList(Request $request)
	{
		$loginUser = Auth::user();
		
		$searchHist = SearchHist::where('owner_id' ,$loginUser->id)
			->where('use_page' ,'COMP_CAND')
			->first();

		$searchHist['result'] = $request->result;
		$searchHist['from_age'] = $request->from_age;
		$searchHist['to_age'] = $request->to_age;
		$searchHist['current_job'] = $request->current_job;
		$searchHist['freeword'] = $request->freeword;

		$searchHist->save();

		$search = $searchHist->toArray();

		$userList = $this->search_can_list($search);

		$parent_id = '2';

		return view('comp.candidate_list' ,compact(
			'userList',
			'search',
			'parent_id',
		));
 
	}


/*************************************
* 検索リスト
**************************************/
	public function search_can_list($param)
	{

		$loginUser = Auth::user();

		$subSQL0 = \DB::table('users')
			->selectRaw("id, TIMESTAMPDIFF(YEAR, users.birthday, CURDATE()) AS age");

		$subSQL1 = \DB::table('interviews')
			->selectRaw("user_id, max(updated_at) as formal_last_update, min(result_id) as result_id")
			->where('interviews.company_id' , $loginUser->company_id)
			->where('interviews.aprove_flag', '1')
			->where('interviews.interview_type' , '1')
			->where('interviews.result_id' , '>', '0')
			->groupBy('interviews.user_id');

		$subSQL2 = \DB::table('interviews')
			->selectRaw("user_id,  max(updated_at) as casual_last_update")
			->where('interviews.company_id' , $loginUser->company_id)
			->where('interviews.aprove_flag', '1')
			->where('interviews.interview_type' , '0')
			->groupBy('interviews.user_id');

		$subSQL3 = \DB::table('interviews')
			->selectRaw("user_id,  max(updated_at) as event_last_update")
			->where('interviews.company_id' , $loginUser->company_id)
			->where('interviews.aprove_flag', '1')
			->where('interviews.interview_type' , '2')
			->groupBy('interviews.user_id');

		$userQuery = \DB::table('users')
			->JoinSub($subSQL0 , 'user_age' ,'user_age.id', 'users.id')
			->leftJoinSub($subSQL1 , 'last_result' ,'last_result.user_id', 'users.id')
			->leftJoinSub($subSQL2 , 'last_interview' ,'last_interview.user_id', 'users.id')
			->leftJoinSub($subSQL3 , 'last_event' ,'last_event.user_id', 'users.id')
			->leftJoin('const_results', 'const_results.id' , '=', 'users.result_id')
			->selectRaw("users.*, age, const_results.name as result_name, DATE_FORMAT( GREATEST( ifnull(casual_last_update,0), ifnull(event_last_update,0), ifnull(formal_last_update,0) ) ,'%Y/%m/%d') as last_update")
			->where(function($query) {
				$query->whereNotNull('casual_last_update')
					->orWhereNotNull('event_last_update')
					->orWhereNotNull('formal_last_update');
				});

		if (!empty($param['result'])) $userQuery = $userQuery->where('users.result_id' , $param['result']);
		if (!empty($param['from_age'])) $userQuery = $userQuery->where('age' ,'>=',  $param['from_age']);
		if (!empty($param['to_age'])) $userQuery = $userQuery->where('age' ,'<',  $param['to_age'] + 10);
//		if (!empty($param['current_job'])) $userQuery = $userQuery->whereIn('users.current_job' ,  $param['current_job']);

		if (!empty($param['freeword'])) {
			$userQuery = $userQuery
				->where(function($query) use  ($loginUser ,$param) {
					$query->where('users.graduation',  'like', "%{$param['freeword']}%")
					->orWhere('users.company',         'like', "%{$param['freeword']}%")
					->orWhere('users.old_company',     'like', "%{$param['freeword']}%")
					->orWhere('users.job_title',       'like', "%{$param['freeword']}%")
					->orWhere('users.job_content',     'like', "%{$param['freeword']}%")
					->orWhere('users.request_carrier', 'like', "%{$param['freeword']}%")
					->orWhere('users.job_detail',      'like', "%{$param['freeword']}%")
					;
				});
		}
		
		$userList = $userQuery->paginate(10);

//ddd($userList);

		$idx = 0;
		foreach ($userList as $user) {

			$catName = array();
			$cats = explode(",", $user->job_cats);
			$len = count($cats);

			for ($i = 0; $i < $len; $i++) {
				$cat = JobCat::find($cats[$i]);
				if ($cat) {
					$catName[] = $cat->name;
				}
			}

			$userList[$idx++]->cat_names = join("/",$catName);
		}

		return $userList;
	}	

}



