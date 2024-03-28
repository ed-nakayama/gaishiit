<?php

namespace App\Http\Controllers\Comp;

use App\Http\Controllers\ClientController;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log; 
use Illuminate\Validation\Rule;


use App\Models\Unit;
use App\Models\Company;
use App\Models\CompMember;
use App\Models\Job;
use App\Models\Interview;
use App\Models\ConstStage;
use App\Models\SearchHist;


class CompClientController extends ClientController
{
	public $loginUser;

	public function __construct()
	{
 		$this->middleware('auth:comp');
	}


/*************************************
* 初期表示
**************************************/
	public function index()
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


		return view('comp.client_list' ,compact(
			'beingList',
			'alreadyList',
		));
	}



/*************************************
* 一覧
**************************************/
	public function list(Request $request)
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

		return redirect('comp/client');
	}




/*************************************
* 初期表示
**************************************/
	public function endIndex()
	{
		$loginUser = Auth::user();
		
		$searchHist = new SearchHist();
		
		$search = $searchHist->toArray();
		$search['only_me'] = '1';


		$request = new Request();

		$endList = $this->search_list($search);

		return view('comp.client_end_list' ,compact(
			'endList',
			'search',
		));
	}


/*************************************
* 一覧
**************************************/
	public function endList(Request $request)
	{
		$loginUser = Auth::user();

		$searchHist = new SearchHist();

		$search = $searchHist->toArray();
		if ( $request->only_me == '1' ) {
			$search['only_me'] = '1';
		} else {
			$search['only_me'] = '';
		}


		$endList = $this->search_list($search);

		return view('comp.client_end_list' ,compact(
			'endList',
			'search',
		));
	}


/*************************************
* 検索リスト
**************************************/
	public function search_list($param)
	{
		
		$loginUser = Auth::user();

		$endQuery = Interview::Join('users', 'interviews.user_id','=','users.id')
//			->Join('companies', 'interviews.company_id','=','companies.id')
//			->leftJoin('units', 'interviews.unit_id','=','units.id')
//			->leftJoin('jobs', 'interviews.job_id','=','jobs.id')
			->leftJoin('const_stages', 'interviews.stage_id','=','const_stages.id')
			->leftJoin('const_statuses', 'interviews.status_id','=','const_statuses.id')
			->leftJoin('const_results', 'interviews.result_id','=','const_results.id');
	
		if ($param['only_me'] == '1') {
			$endQuery = $endQuery
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
	           });

		} else {
			$endQuery = $endQuery
				->leftJoin('companies', function ($join) use ($loginUser) {
	            	$join->on('interviews.company_id','=','companies.id')
		 				->where('interviews.interview_type' , '0')
			 			->where('interviews.interview_kind' , '0')
						->where('companies.id' , $loginUser->company_id);
				})
				->leftJoin('units', function ($join) use ($loginUser) {
	                $join->on('interviews.unit_id','=','units.id')
		 				->where('interviews.interview_type' , '0')
		 				->where('interviews.interview_kind' , '1')
			 			->where('units.company_id' , $loginUser->company_id);
	           })
				->leftJoin('jobs', function ($join) use ($loginUser) {
	                $join->on('interviews.job_id','=','jobs.id')
					->where(function($query) {
			    		$query->where('interviews.interview_type' ,'1')
							->orWhere('interviews.interview_kind', '2');
						})
					->where('jobs.company_id' , $loginUser->company_id);
	           });
		}

	         
		$endQuery = $endQuery
			->selectRaw('interviews.*,' .
						 'companies.person as company_person,' .
						 'units.name as unit_name, units.person as unit_person,' .
						 'jobs.name as job_name, jobs.person as job_person,' .
						 'const_stages.name as stage_name,' .
						 'const_statuses.name as status_name,' .
						 'users.id as user_id, users.name as user_name'
						 )
			->where(function($query) {
			    $query->where('interviews.interview_type' , '0')
					->orWhere('interviews.interview_type' , '1');
			})
			->where('interviews.company_id' , $loginUser->company_id)
			->where('interviews.aprove_flag', '1')
			->where('interviews.status_id', '9');

		if ($param['only_me'] == '1') {
			$endQuery = $endQuery
				->where(function($query) use($loginUser) {
				    $query->where('companies.person' , 'like' ,"%$loginUser->id%")
						->orWhere('units.person' , 'like' ,"%$loginUser->id%")
						->orWhere('jobs.person' , 'like' ,"%$loginUser->id%");
				});
		}

		$endList = $endQuery->orderBy('interviews.updated_at' , 'desc')->paginate(20);


		$idx = 0;
		foreach ($endList as $list) {
			
			$loc = array();
			if ($list->interview_type == '0' && $list->interview_kind == '0') {
				if ( !empty($list->company_person) ) $loc = explode(',', $list->company_person);

			} elseif ($list->interview_type == '0' && $list->interview_kind == '1') {
				if ( !empty($list->unit_person) ) $loc = explode(',', $list->unit_person);

			} elseif ( ($list->interview_type == '0' && $list->interview_kind == '2') || $list->interview_type == '1' ) {
				if ( !empty($list->job_person) ) $loc = explode(',', $list->job_person);

			};
		
			if ( !empty($loc) ) {
				$ln = CompMember::whereIn('id' ,$loc)->get();

				$person_name = array();
				for ($i = 0; $i < count($ln); $i++) {
					$person_name[] = $ln[$i]['name'];
				}

				$endList[$idx++]->person_name = implode('/', $person_name);
			} else {
				$endList[$idx++]->person_name = '';
			}
		}


		return $endList;
	}


/*************************************
* 初期表示
**************************************/
	public function enter()
	{
		$loginUser = Auth::user();
		
		$searchHist = new SearchHist();

		$search = $searchHist->toArray();
		$search['only_me'] = '1';


		$request = new Request();

		$endList = $this->enter_search_list($search);

		return view('comp.client_enter_list' ,compact(
			'endList',
			'search',
		));
	}


/*************************************
* 一覧
**************************************/
	public function enterList(Request $request)
	{
		$loginUser = Auth::user();

		$searchHist = new SearchHist();

		$search = $searchHist->toArray();
		if ( $request->only_me == '1' ) {
			$search['only_me'] = '1';
		} else {
			$search['only_me'] = '';
		}


		$endList = $this->enter_search_list($search);

		return view('comp.client_enter_list' ,compact(
			'endList',
			'search',
		));
	}


/*************************************
* 一覧
**************************************/
	public function enterSave(Request $request)
	{
		$loginUser = Auth::user();

		$interview = Interview::find($request->interview_id);

		$interview->entrance_date = $request->entrance_date;
		$interview->last_update_id = $loginUser->id;
		$interview->save();

		return redirect('/comp/client/enter/list?only_me=' . $request->only_me);
	}


/*************************************
* 検索リスト
**************************************/
	public function enter_search_list($param)
	{
		
		$loginUser = Auth::user();


		$endQuery = Interview::Join('users', 'interviews.user_id','=','users.id')
			->Join('jobs', 'interviews.job_id','=','jobs.id')
			->leftJoin('units', 'interviews.unit_id','=','units.id')
			->leftJoin('const_stages', 'interviews.stage_id','=','const_stages.id')
			->leftJoin('const_statuses', 'interviews.status_id','=','const_statuses.id')
			->leftJoin('const_results', 'interviews.result_id','=','const_results.id');

		if ($param['only_me'] == '1') {
			$endQuery = $endQuery->where('jobs.person' , 'like' ,"%$loginUser->id%");
		}

		$endQuery = $endQuery
			->selectRaw('interviews.*,' .
						 'units.name as unit_name,' .
						 'jobs.name as job_name, jobs.person as person,' .
						 'const_stages.name as stage_name,' .
						 'const_statuses.name as status_name,' .
						 'users.id as user_id, users.name as user_name'
						 )
			->where('interviews.company_id' , $loginUser->company_id)
			->where('interviews.interview_type' ,'1')
			->where('interviews.aprove_flag', '1')
			->where('interviews.result_id', '1')
			->where('interviews.status_id', '9')
			;

		$endList = $endQuery
			->orderByRaw('interviews.entrance_date IS NULL DESC')
			->orderBy('interviews.entrance_date' , 'desc')
			->paginate(20);

		$idx = 0;
		foreach ($endList as $list) {
			
		if ( !empty($list->person) ) {
			$loc = explode(',', $list->person);
			$ln = CompMember::whereIn('id' ,$loc)->get();

				$person_name = array();
				for ($i = 0; $i < count($ln); $i++) {
					$person_name[] = $ln[$i]['name'];
				}

				$endList[$idx++]->person_name = implode('/', $person_name);
			} else {
				$endList[$idx++]->person_name = '';
			}
		}

		return $endList;
	}


}
