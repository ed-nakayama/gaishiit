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


class CompMypageController extends Controller
{
	public function __construct()
    {
        $this->middleware('auth:comp');
    }
    
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
		$loginUser = Auth::user();

		$searchHist = SearchHist::where('owner_id' ,$loginUser->id)
			->where('use_page' ,'COMP_SAVE')
			->first();
			
		if (!$searchHist) {
			$searchHist = SearchHist::create([
				'owner_id' => $loginUser->id,
				'use_page' => 'COMP_SAVE',
			]);
		}

//		$search = $searchHist->toArray();
		$search = $searchHist;

		$userList = $this->search_list($searchHist);

   		return view('comp.mypage' ,compact(
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
			->selectRaw("users.*, age")
			->where(function($query) use  ($loginUser) {
				$query->whereNull('users.no_company')
				->orWhere('users.no_company','not LIKE' , "%{$loginUser->company_id}%");
			})
			->orderBy('created_at','desc');

		if (!empty($param['from_age'])) $userQuery = $userQuery->where('age' ,'>=',  $param['from_age']);
		if (!empty($param['to_age'])) $userQuery = $userQuery->where('age' ,'<',  $param['to_age'] + 10);
//		if ($param['current_job'] != '') $userQuery = $userQuery->whereIn('users.current_job' ,  $param['current_job'] );

		// 希望業種
		if (!empty($param['request_bus_cats'])) {
			$job = explode(',', $param['request_bus_cats']);

			if (count($job) == 1) {
				$userQuery = $userQuery->where('users.business_cats',  'like', "%{$job[0]}%" );
			} else {
				$userQuery = $userQuery
					->where(function($query) use ($job) {
						$query->where('users.business_cats' , 'like', "%{$job[0]}%" );
						for ($i = 1; $i < count($job); $i++) {
							$query = $query->orWhere('users.business_cats' , 'like', "%{$job[$i]}%" );
						}
					});
			}
		}

		// 希望職種
		if (!empty($param['request_job_cat_details'])) {
			$job = explode(',', $param['request_job_cat_details']);

			if (count($job) == 1) {
				$userQuery = $userQuery->where('users.job_cat_details',  'like', "%{$job[0]}%" );
			} else {
				$userQuery = $userQuery
					->where(function($query) use ($job) {
						$query->where('users.job_cat_details' , 'like', "%{$job[0]}%" );
						for ($i = 1; $i < count($job); $i++) {
							$query = $query->orWhere('users.job_cat_details' , 'like', "%{$job[$i]}%" );
						}
					});
			}
		}


		// 希望勤務地
		if (!empty($param['location'])) {
			$loc = explode(',', $param['location']);
			
			if (count($loc) == 1) {
				$userQuery = $userQuery->where('users.request_location',  'like', "%{$loc[0]}%" );
			} else {
				$userQuery = $userQuery
					->where(function($query) use  ($loc) {
						$query->where('users.request_location' , 'like', "%{$loc[0]}%" );
						for ($i = 1; $i < count($loc); $i++) {
							$query = $query->orWhere('users.request_location' , 'like', "%{$loc[$i]}%" );
						}
					});
			}
		}

/*
		if ($param['freeword'] != '') {
			$userQuery = $userQuery
				->where(function($query) use  ($loginUser) {
					$query->where('users.graduation' , 'like', "%{$param['freeword']}%")
					->orWhere('users.company' , 'like', "%{$param['freeword']}%")
					->orWhere('users.job_content' , 'like', "%{$param['freeword']}%")
					->orWhere('users.japanese_background' , 'like', "%{$param['freeword']}%")
					->orWhere('users.english_background' , 'like', "%{$param['freeword']}%")
					;
				});
		}
*/
		$userList = $userQuery->limit(10)->get();

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
//		if (!empty($param['request_cat'])) $userQuery = $userQuery->where('users.job_cats' , $param['request_cat'] );
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

		$idx = 0;
		foreach ($userList as $user) {

			$catName = array();
			$cats = explode(",", $user->job_cat_details);
			$len = count($cats);

			for ($i = 0; $i < $len; $i++) {
				$cat = JobCatDetail::find($cats[$i]);
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
* 初期表示
**************************************/
	public function index_progress()
	{
		$loginUser = Auth::user();

		$beingList = Interview::Join('users', 'interviews.user_id','=','users.id')
			->leftJoin('const_stages', 'interviews.stage_id','=','const_stages.id')
			->leftJoin('companies', function ($join) use ($loginUser) {
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
			->selectRaw('interviews.*,' .
						 'units.name as unit_name,' .
						 'jobs.name as job_name,' .
						 'const_stages.name as stage_name,' .
						 'users.id as user_id, users.name as user_name')
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

		$alreadyList = Interview::Join('users', 'interviews.user_id','=','users.id')
			->leftJoin('const_stages', 'interviews.stage_id','=','const_stages.id')
			->leftJoin('companies', function ($join) use ($loginUser) {
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
			->selectRaw('interviews.*,' .
						 'units.name as unit_name,' .
						 'jobs.name as job_name,' .
						 'const_stages.name as stage_name ,' .
						 'users.id as user_id, users.name as user_name')
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
