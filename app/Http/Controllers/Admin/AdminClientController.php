<?php

namespace App\Http\Controllers\Admin;

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


class AdminClientController extends ClientController
{
	public $loginUser;

	public function __construct()
	{
 		$this->middleware('auth:admin');
	}


/*************************************
* 初期表示
**************************************/
	public function endIndex()
	{
		$loginUser = Auth::user();
		
		$search['only_me'] = '1';

		$request = new Request();

		$endList = $this->search_list($search);

		return view('admin.client_end_list' ,compact(
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

		if ( $request->only_me == '1' ) {
			$search['only_me'] = '1';
		} else {
			$search['only_me'] = '';
		}


		$endList = $this->search_list($search);

		return view('admin.client_end_list' ,compact(
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

		$comp = Interview::Join('companies', 'interviews.company_id', 'companies.id')
			->where('companies.agency_flag' , '1')
			->where('interviews.interview_type' , '0')
			->where('interviews.interview_kind' , '0')
			->where('interviews.aprove_flag', '1')
			->where('interviews.status_id', 9)
			->selectRaw('interviews.*');


		$unit = Interview::Join('companies', 'interviews.company_id', 'companies.id')
			->where('companies.agency_flag' , '1')
			->join('units', 'interviews.unit_id','=','units.id')
			->where('interviews.interview_type' , '0')
			->where('interviews.interview_kind' , '1')
			->where('interviews.aprove_flag', '1')
			->where('interviews.status_id', 9)
			->selectRaw('interviews.*');


		$endQuery = Interview::Join('companies', 'interviews.company_id', 'companies.id')
			->where('companies.agency_flag' , '1')
			->join('jobs','interviews.job_id', 'jobs.id')
			->whereIn('interviews.interview_type', [0, 1])
			->where('interviews.interview_kind' , '2')
			->where('interviews.aprove_flag', '1')
			->where('interviews.status_id', 9)
			->selectRaw('interviews.*');

		
		$endList = $endQuery
			->union($comp)
			->union($unit)
			->orderBy('updated_at' , 'desc')
			->paginate(20);

		$i = 0;
		$cnt = count($endList);
		for ($i = 0; $i < $cnt; $i++) {
			$endList[$i]->getUser();
//			$endList[$i]->getCompany();
			$endList[$i]->getUnit();
			$endList[$i]->getJob();
			$endList[$i]->getStage();
			$endList[$i]->getStatus();
			$endList[$i]->getPerson();
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

		return view('admin.client_enter_list' ,compact(
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

		return view('admin.client_enter_list' ,compact(
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

		return redirect('/admin/client/enter/list?only_me=' . $request->only_me);
	}


/*************************************
* 検索リスト
**************************************/
	public function enter_search_list($param)
	{
		
		$loginUser = Auth::user();

		$endQuery = Interview::Join('companies', 'interviews.company_id', 'companies.id')
			->where('companies.agency_flag' , '1');

		if ($param['only_me'] == '1') {
			$endQuery = $endQuery->Join('jobs', 'interviews.job_id','=','jobs.id')
				->where('jobs.person' , 'like' ,"%$loginUser->id%");
		}

		$endList = $endQuery
			->where('interviews.interview_type' ,'1')
			->where('interviews.aprove_flag', '1')
			->where('interviews.result_id', '1')
			->where('interviews.status_id', '9')
			->selectRaw('interviews.*')
			->orderByRaw('interviews.entrance_date IS NULL DESC')
			->orderBy('interviews.entrance_date' , 'desc')
			->paginate(20);

		$i = 0;
		$cnt = count($endList);
		for ($i = 0; $i < $cnt; $i++) {
			$endList[$i]->getUser();
//			$endList[$i]->getCompany();
			$endList[$i]->getUnit();
			$endList[$i]->getJob();
			$endList[$i]->getStage();
			$endList[$i]->getStatus();
			$endList[$i]->getPerson();
		}

		return $endList;
	}


}
