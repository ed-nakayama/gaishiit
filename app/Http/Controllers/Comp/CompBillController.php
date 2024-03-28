<?php

namespace App\Http\Controllers\Comp;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log; 

use Storage;
use Hashids\Hashids;

use App\Models\Bill;
use App\Models\Interview;
use App\Models\Company;
use App\Mail\RegisterToAdminAccount;
use App\Models\ClaimMonth;


class CompBillController extends Controller
{
	public function __construct()
	{
 		$this->middleware('auth:comp');
	}


/*************************************
* 都度請求一覧
**************************************/
	public function claimEvery()
	{
		$loginUser = Auth::user();

		$start_date = date('Y/m/01',strtotime("-1 month"));

		$intList = Interview::join('companies' ,'interviews.company_id' ,'companies.id')
			->join('users' ,'interviews.user_id' ,'users.id')
			->join('jobs' ,'interviews.job_id' ,'jobs.id')
			->selectRaw('interviews.* ,users.name as user_name ,jobs.name as job_name ,jobs.job_code as job_code ,companies.name as company_name')
 			->where('interviews.company_id' , $loginUser->company_id)
			->where('interviews.interview_type' ,'1' )
			->where('interviews.result_id', '1')
			->where(function($query) use  ($start_date) {
				$query->whereNull('interviews.entrance_date')
				->orWhere('interviews.entrance_date' ,'>=' ,$start_date);
			})

			
			->orderBy('entrance_date')
			->get();

		return view('comp.claim_every' ,compact(
			'intList',
		));

	}


/*************************************
* 年間／月間請求一覧
**************************************/
	public function claimMonthly()
	{
		$loginUser = Auth::user();

		$comp = Company::find($loginUser->company_id);

		$start_date = date('Y/m/01',strtotime("-1 month"));

		for ($i = 0; $i < 7; $i++) {
			$title[] = date('Y/m/01',strtotime(($i - 1) . " month"));
		}
		
		for ($i = 0; $i < 7; $i++) {
			$temp_date = date('Y/m/01',strtotime(($i - 1) . " month"));
			$mon = ClaimMonth::where('company_id' ,$loginUser->company_id)
				->where('claim_date' ,$temp_date)
				->first();

			if (empty($mon)) {
				$mon = ClaimMonth::create([
					'company_id'  => $comp->id,
					'claim_date'  => $temp_date,
		        ]);
			}
		
			$comp['mon' . $i] = $mon->amount;
		}

		return view('comp.claim_monthly' ,compact(
			'start_date',
			'title',
			'comp',
		));

	}


}
