<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\ClientController;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;


use App\Models\Interview;


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
			$endList[$i]->getCompany();
			$endList[$i]->getUnit();
			$endList[$i]->getJob();
			$endList[$i]->getStage();
			$endList[$i]->getStatus();
			$endList[$i]->getPerson();
		}

		return view('admin.client_end_list' ,compact(
			'endList',
		));
	}


}
