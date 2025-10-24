<?php

namespace App\Http\Controllers\Comp;

use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Information;
use App\Models\SearchHist;
use App\Models\JobCat;
use App\Models\JobCatDetail;
use App\Models\ConstLocation;
use App\Models\Interview;
use App\Models\User;


class CompMypageController extends Controller
{
	public function __construct()
    {
        $this->middleware('auth:comp');
    }


/*************************************
* 検索リスト
**************************************/
	public function search(Request $request)
	{
		$loginUser = Auth::user();

		$searchHist = SearchHist::where('owner_id' ,$loginUser->id)
			->where('use_page' ,'COMP_SAVE')
			->first();

		$searchHist['from_age'] = $request->from_age;
		$searchHist['to_age'] = $request->to_age;
//		$searchHist['current_job'] = $request->current_job;

		if (!empty($request->location)) {
			$loc = implode(",", $request->location);
			$searchHist['location'] = $loc;
		} else {
			$searchHist['location'] = '';
		}
		
		if (!empty($request->buscat_sel)) {
			$cat = implode(",", $request->buscat_sel);
			$searchHist['request_bus_cats'] = $cat;
		} else {
			$searchHist['request_bus_cats'] = '';
		}

		if (!empty($request->jobcat_sel)) {
			$cat = implode(",", $request->jobcat_sel);
			$searchHist['request_job_cat_details'] = $cat;
		} else {
			$searchHist['request_job_cat_details'] = '';
		}

		
//		$searchHist['freeword'] = $request->freeword;

		$searchHist->save();


		return redirect('comp/mypage');
//        return redirect()->back();
	}



/*************************************
* 一覧 候補者検索
**************************************/
	public function index_main()
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

		$userList = $this->search_list_main($search);

		return view('comp.mypage_main' ,compact(
			'userList',
			'search',
		));
 	}


/*************************************
* 一覧 候補者検索
**************************************/
	public function list_main(Request $request)
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

		$userList = $this->search_list_main($search);

	
		return view('comp.mypage_main' ,compact(
			'userList',
			'search',
		));
	}


/*************************************
* 検索リスト
**************************************/
	public function search_list_main($param)
	{

		$loginUser = Auth::user();

		$subSQL0 = \DB::table('users')
			->selectRaw("id, TIMESTAMPDIFF(YEAR, users.birthday, CURDATE()) AS age")
			->where('aprove_flag' , '1');
		
		$userQuery = User::JoinSub($subSQL0 , 'user_age' ,'user_age.id', 'users.id')
			->selectRaw("users.*, age")
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

		return $userList;
	}
	


/*************************************
* 一覧 新しい候補者検索
**************************************/
	public function index_newuser()
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

		$userList = $this->search_list_newuser($search);

		return view('comp.mypage_newuser' ,compact(
			'userList',
			'search',
		));
 
 	}


/*************************************
* 一覧 新しい候補者検索
**************************************/
	public function list_newuser(Request $request)
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

		$userList = $this->search_list_newuser($search);

	
		return view('comp.mypage_newuser' ,compact(
			'userList',
			'search',
		));
	}


/*************************************
* 検索リスト
**************************************/
	public function search_list_newuser($param)
	{

		$loginUser = Auth::user();

		$subSQL0 = \DB::table('users')
			->selectRaw("id, TIMESTAMPDIFF(YEAR, users.birthday, CURDATE()) AS age");
		
		$userQuery = User::JoinSub($subSQL0 , 'user_age' ,'user_age.id', 'users.id')
			->selectRaw("users.*, age")
			->where(function($query) use  ($loginUser) {
				$query->whereNull('users.no_company')
				->orWhere('users.no_company','not LIKE' , "%{$loginUser->company_id}%");
			});

		if (!empty($param['from_age'])) $userQuery = $userQuery->where('age' ,'>=',  $param['from_age']);
		if (!empty($param['to_age'])) $userQuery = $userQuery->where('age' ,'<',  $param['to_age'] + 10);
		if (!empty($param['location'])) $userQuery = $userQuery->where('users.request_location' , $param['location'] );
		if (!empty($param['request_cat'])) $userQuery = $userQuery->where('users.job_cat_details' , $param['request_cat'] );

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

		return $userList;
	}
	


/*************************************
* 初期表示
**************************************/
	public function index_progress()
	{
		$loginUser = Auth::user();

		$beingList = Interview::leftJoin('companies', function ($join) use ($loginUser) {
                $join->on('interviews.company_id','=','companies.id')
		 			->where('interviews.interview_type' , '0')
		 			->where('interviews.interview_kind' , '0')
		 			->where('companies.id' , $loginUser->company_id)
					->where('companies.person' , 'like' ,"%$loginUser->id%");
           })
			->leftJoin('units', function ($join) use ($loginUser) {
                $join->on('interviews.unit_id','=','units.id')
		 			->where('interviews.interview_type' , '0')
		 			->where('interviews.interview_kind' , '1')
		 			->where('units.company_id' , $loginUser->company_id)
					->where('units.person' , 'like' ,"%$loginUser->id%");
           })
			->leftJoin('jobs', function ($join) use ($loginUser) {
                $join->on('interviews.job_id','=','jobs.id')
					->where(function($query) {
			    		$query->where('interviews.interview_type' ,'1')
							->orWhere('interviews.interview_kind', '2');
						})
					->where('jobs.company_id' , $loginUser->company_id)
					->where('jobs.person' , 'like' ,"%$loginUser->id%");
           })
			->selectRaw('interviews.*')
			->where('interviews.aprove_flag', '1')
			->whereNotIn('interviews.status_id', [4, 9])

			->where(function($query) {
			    $query->where('interviews.interview_type' , '0')
					->orWhere('interviews.interview_type' , '1');
			})
			->where(function($query) use($loginUser) {
			    $query->where('companies.person' , 'like' ,"%$loginUser->id%")
					->orWhere('units.person' , 'like' ,"%$loginUser->id%")
					->orWhere('jobs.person' , 'like' ,"%$loginUser->id%");
			})
			->orderBy('interviews.updated_at')
			->get();

//dd($beingList);

		$i = 0;
		$cnt = count($beingList);
		for ($i = 0; $i < $cnt; $i++) {
			$beingList[$i]->getUser();
			$beingList[$i]->getCompany();
			$beingList[$i]->getUnit();
			$beingList[$i]->getJob();
		}


		$alreadyList = Interview::leftJoin('companies', function ($join) use ($loginUser) {
                $join->on('interviews.company_id','=','companies.id')
		 			->where('interviews.interview_type' , '0')
		 			->where('interviews.interview_kind' , '0')
		 			->where('companies.id' , $loginUser->company_id)
					->where('companies.person' , 'like' ,"%$loginUser->id%");
           })
			->leftJoin('units', function ($join) use ($loginUser) {
                $join->on('interviews.unit_id','=','units.id')
		 			->where('interviews.interview_type' , '0')
		 			->where('interviews.interview_kind' , '1')
		 			->where('units.company_id' , $loginUser->company_id)
					->where('units.person' , 'like' ,"%$loginUser->id%");
           })
			->leftJoin('jobs', function ($join) use ($loginUser) {
                $join->on('interviews.job_id','=','jobs.id')
					->where(function($query) {
			    		$query->where('interviews.interview_type' ,'1')
							->orWhere('interviews.interview_kind', '2');
						})
					->where('jobs.company_id' , $loginUser->company_id)
					->where('jobs.person' , 'like' ,"%$loginUser->id%");
           })
			->selectRaw('interviews.*')
			->where(function($query) use($loginUser) {
			    $query->where('companies.person' , 'like' ,"%$loginUser->id%")
					->orWhere('units.person' , 'like' ,"%$loginUser->id%")
					->orWhere('jobs.person' , 'like' ,"%$loginUser->id%");
			})
			->where('interviews.aprove_flag', '1')
			->where('interviews.status_id' , '4')
			->where(function($query) {
			    $query->where('interviews.interview_type' , '0')
					->orWhere('interviews.interview_type' , '1');
			})
			->orderBy('interviews.updated_at')
			->get();

		$i = 0;
		$cnt = count($alreadyList);
		for ($i = 0; $i < $cnt; $i++) {
			$alreadyList[$i]->getUser();
			$alreadyList[$i]->getCompany();
			$alreadyList[$i]->getUnit();
			$alreadyList[$i]->getJob();
		}

		return view('comp.mypage_progress' ,compact(
			'beingList',
			'alreadyList',
		));
	}



/*************************************
* 一覧
**************************************/
	public function list_progress(Request $request)
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

		$interview->entrance_date = $request->entrance_date;

		$interview->interviewer = $request->interviewer;
		$interview->comment = $request->comment;
		$interview->last_update_id = $loginUser->id;


       if ( $request->has('interview_date') ) {
			$interview->interview_date = $request->interview_date;
		}
		
		$interview->save();

		return redirect('comp/mypage/progress');
	}



}
