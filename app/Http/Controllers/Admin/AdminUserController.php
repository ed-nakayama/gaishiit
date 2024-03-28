<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

use Illuminate\Http\Request;

use App\Models\User;
use App\Models\JobCat;
use App\Models\JobCatDetail;
use App\Models\SearchHist;
use App\Models\Interview;
use App\Models\Company;
use App\Models\Unit;
use App\Models\Job;
use App\Models\ConstLocation;
use App\Models\BusinessCatDetail;

use App\Mail\AproveToUser;
use App\Mail\AproveToComp;
use App\Mail\RejectToUser;

class AdminUserController extends UserController
{

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
		
		$aprove = 0;
		$userList = $this->search_list($aprove ,$request);

		return view('admin.user_list' ,compact(
			'userList',
		));

	}


/*************************************
* 検索リスト
**************************************/
	public function search_list($aprove ,$request)
	{
		$subSQL0 = \DB::table('users')
			->selectRaw("id, TIMESTAMPDIFF(YEAR, users.birthday, CURDATE()) AS age");
		
		$userQuery = \DB::table('users')
			->JoinSub($subSQL0 , 'user_age' ,'user_age.id', 'users.id')
			->leftJoin('const_locations', 'users.request_location','=','const_locations.id')
			->selectRaw("users.*, age ,const_locations.name as location_name")
			->where('aprove_flag' ,$aprove)
			->orderBy('created_at' ,'desc');

		$userList = $userQuery->paginate(20);

		return $userList;
	}
	


/*************************************
* 一覧 候補者検索
**************************************/
	public function aprove(Request $request)
	{
		if ( isset($request->sel_aprove) ) {
			if ($request->sel_aprove == '1') { // 承認
				$sel_aprove = '1';
			} elseif ($request->sel_aprove == '2') { // 否認
				$sel_aprove = '2';
			} else { // リジェクト
				$sel_aprove = '0';
			}
		} else {
			$sel_aprove = '0';
		}

		if (!empty($request->sel)) {
			foreach($request->sel as $key => $val) {
				$user = User::find($val);
				$user->aprove_flag = $sel_aprove;
				$user->save();

				if ($sel_aprove == 1) { // 承認のお知らせ
					Mail::send(new AproveToUser($user));
//		 			Mail::send(new AproveToComp($member));

				} else if ($sel_aprove == 2) { // 否認のお知らせ
					Mail::send(new RejectToUser($user));
		 		}
			}
  		}


        return redirect()->back();
//		return redirect('admin/user/list');
	}


/*************************************
* 一覧 候補者履歴
**************************************/
	public function aproveHist(Request $request)
	{
		if ( isset($request->sel_aprove) ) {
			if ($request->sel_aprove == '1') { // 承認
				$sel_aprove = '1';
			} elseif ($request->sel_aprove == '2') { // 承認
				$sel_aprove = '2';
			} else { // リジェクト
				$sel_aprove = '0';
			}
		} else {
			$sel_aprove = '1';
		}

		$userList = $this->search_list($sel_aprove, $request);

		
		return view('admin.user_hist' ,compact(
			'userList',
			'sel_aprove',
		));
	}


/*************************************
* 一覧 候補者検索
**************************************/
	public function histBack(Request $request)
	{
		if ($request->aprove == '1') { // 承認
			$aprove = '1';
			$sel_aprove = '2';
		} else { // リジェクト
			$sel_aprove = '1';
			$aprove = '2';
		}

		if (!empty($request->sel)) {
			foreach($request->sel as $key => $val) {
				$user = User::find($val);
				$user->aprove_flag = $aprove;
				$user->save();
			}
  		}

		$userList = $this->search_list($sel_aprove, $request);

		return view('admin.user_hist' ,compact(
			'userList',
			'sel_aprove',
		));
	}


/*************************************
* ユーザ情報取得
**************************************/
	public function get_user($user_id)
	{
		$userInfo = User::leftJoin('const_englishes as eng','users.english','=','eng.id')
			->leftJoin('const_englishes as jpn','users.japanese','=','jpn.id')
			->leftJoin('const_prefs','users.pref' ,'=' ,'const_prefs.id')
			->selectRaw('users.*  ,eng.name as english_name ,jpn.name as japanese_name  ,const_prefs.name as pref_name ')
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


		// 職種名の取得
		$catName = array();
		$cats = explode(",", $userInfo->job_cats);
		$catList = JobCat::select('name')->whereIn('id' ,$cats)->get();
		foreach ($catList as $cat) {
			$catName[] = $cat->name;
		}
		$userInfo['job_cat_name'] = join(" / ",$catName);

		// 職種名の取得
		$catDetailName = array();
		$cat_details = explode(",", $userInfo->job_cat_details);
		$catDetailList = JobCatDetail::select('name')->whereIn('id' ,$cat_details)->get();
		foreach ($catDetailList as $cat) {
			$catDetailName[] = $cat->name;
		}
		$userInfo['job_cat_detail_name'] = join(" / ",$catDetailName);


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
		$user_id = $request->user_id;

		$userInfo = $this->get_user($user_id);

		$interviewList = Interview::leftJoin('companies','interviews.company_id','=','companies.id')
			->leftJoin('units','interviews.unit_id','=','units.id')
			->leftJoin('jobs','interviews.job_id','=','jobs.id')
			->selectRaw('interviews.* ,companies.name as company_name ,units.name as unit_name ,jobs.name as job_name ')
			->where('interviews.user_id' ,$user_id)
			->whereNotNull('interviews.entrance_date')
			->get();


		$subSQL = Interview::selectRaw("user_id, company_id, max(updated_at) as last_update")
//			->whereNull('interviews.entrance_date')
			->groupBy('user_id','company_id')
			->toSql();

		$ownerList = Interview::joinSub($subSQL , 'int2', function ($join) {
				$join->on('interviews.user_id', '=', 'int2.user_id')
					->whereRaw('interviews.company_id = int2.company_id')
					->whereRaw('interviews.updated_at = last_update')
					;
			    })
            ->leftJoin('companies','interviews.company_id','=','companies.id')
			->leftJoin('units','interviews.unit_id','=','units.id')
			->leftJoin('jobs','interviews.job_id','=','jobs.id')
			->leftJoin('events','interviews.event_id','=','events.id')
			->selectRaw('interviews.* ,companies.name as company_name ,units.name as unit_name ,jobs.name as job_name ,events.name as event_name ')
			->where('interviews.user_id' ,$user_id)
//			->whereNull('interviews.entrance_date')
			->get();

		$parent_id = $request->parent_id;

		return view('admin.user_detail' ,compact(
			'userInfo',
			'interviewList',
			'ownerList',
			'parent_id',
		));
	}


/*************************************
* 状態変更
**************************************/
	public function change( Request $request )
	{
		$user = User::find($request->user_id);

		if ($user->aprove_flag == '0') {
			$user->aprove_flag = $request->aprove;
			$user->save();

			if ($request->aprove == 1) { // 承認のお知らせ
				Mail::send(new AproveToUser($user));
//				Mail::send(new AproveToComp($member));

			} else if ($request->aprove == 2) { // 否認のお知らせ
				Mail::send(new RejectToUser($user));
  			}
		}
		
		return redirect()->route('admin.user.detail', ['user_id'=>$user->id] );
	}


/*************************************
* 一覧 候補者管理
**************************************/
	public function canIndex()
	{
		$loginUser = Auth::user();
		
		$searchHist = SearchHist::where('owner_id' ,$loginUser->id)
			->where('use_page' ,'ADMIN_CAND')
			->first();
			
		if (!$searchHist) {
			$searchHist = SearchHist::create([
				'owner_id'        => $loginUser->id,
				'use_page'       => 'ADMIN_CAND',
			]);
		}
		
		$search = $searchHist->toArray();

		$userList = $this->search_can_list($search);

		return view('admin.candidate_list' ,compact(
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
			->where('use_page' ,'ADMIN_CAND')
			->first();

		$searchHist['result'] = $request->result;
		$searchHist['from_age'] = $request->from_age;
		$searchHist['to_age'] = $request->to_age;
		$searchHist['current_job'] = $request->current_job;
		$searchHist['freeword'] = $request->freeword;

		$searchHist->save();

		$search = $searchHist->toArray();

		$userList = $this->search_can_list($search);

		return view('admin.candidate_list' ,compact(
			'userList',
			'search',
		));
 
	}


/*************************************
* 候補者管理 検索リスト
**************************************/
	public function search_can_list($param)
	{
		$subSQL0 = \DB::table('users')
			->selectRaw("id, TIMESTAMPDIFF(YEAR, users.birthday, CURDATE()) AS age");

		$userQuery = \DB::table('users')
			->JoinSub($subSQL0 , 'user_age' ,'user_age.id', 'users.id')
			->leftJoin('const_locations','users.request_location','=','const_locations.id')
			->selectRaw("users.*, age ,const_locations.name as location_name");

		if ($param['result'] != '') $userQuery = $userQuery->where('users.result_id' , $param['result']);
		if ($param['from_age']!= '') $userQuery = $userQuery->where('age' ,'>=',  $param['from_age']);
		if ($param['to_age'] != '') $userQuery = $userQuery->where('age' ,'<',  $param['to_age'] + 10);
		if ($param['current_job'] != '') $userQuery = $userQuery->whereIn('users.job_cats' ,  $param['current_job']);

		if ($param['freeword'] != '') {
			$userQuery = $userQuery
				->where(function($query) {
					$query->where('users.graduation' , 'like', "%{$param['freeword']}%")
					->orWhere('users.company' , 'like', "%{$param['freeword']}%")
					->orWhere('users.job_content' , 'like', "%{$param['freeword']}%")
					->orWhere('users.japanese_background' , 'like', "%{$param['freeword']}%")
					->orWhere('users.english_background' , 'like', "%{$param['freeword']}%")
					;
				});
		}
		
		$userList = $userQuery->orderBy('created_at' ,'desc')->paginate(20);

//ddd($userList);

		$idx = 0;
		foreach ($userList as $user) {

			$catName = array();
			$cats = explode(",", $user->job_cats);
			$len = count($cats);

			for ($i = 0; $i < $len; $i++) {
				$cat = JobCatDetail::find($cats[$i]);
				if ($cat) {
					$catName[] = $cat->name;
				}
			}

			$userList[$idx++]->cat_names = join("/",$catName);
		}

		return $userList;
	}	


/*************************************
* オーナーシップ 検索リスト
**************************************/
	public function ownership()
	{
		$subSQL = Interview::selectRaw("user_id, company_id, max(updated_at) as last_update")
//			->whereNull('interviews.entrance_date')
			->groupBy('user_id','company_id')
			->toSql();

		$ownerList = Interview::joinSub($subSQL , 'int2', function ($join) {
				$join->on('interviews.user_id', '=', 'int2.user_id')
					->whereRaw('interviews.company_id = int2.company_id')
					->whereRaw('interviews.updated_at = last_update')
					;
			})
			->join('users','interviews.user_id','=','users.id')
			->leftJoin('companies','interviews.company_id','=','companies.id')
			->leftJoin('units','interviews.unit_id','=','units.id')
			->leftJoin('jobs','interviews.job_id','=','jobs.id')
			->leftJoin('events','interviews.event_id','=','events.id')
			->selectRaw('interviews.* ,users.name as user_name, companies.name as company_name ,units.name as unit_name ,jobs.name as job_name ,events.name as event_name ')
//			->where('interviews.user_id' ,$user_id)
//			->whereNull('interviews.entrance_date')
			->paginate(20);

		return view('admin.ownership_list' ,compact(
			'ownerList',
		));

	}	



}
