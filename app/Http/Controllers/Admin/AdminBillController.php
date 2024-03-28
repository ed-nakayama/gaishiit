<?php

namespace App\Http\Controllers\Admin;

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


class AdminBillController extends Controller
{
	public function __construct()
	{
 		$this->middleware('auth:admin');
	}



/*************************************
* 
**************************************/
	public function index()
	{
		$today = date('Y/m/d');
		
		$start_year = date('Y') . '/07/01';
		
		if (strtotime($today) >= strtotime($start_year) ) {
			$fiscal_year = date('Y');
		} else {
			$fiscal_year = date('Y') - 1;
		}

		$billTotal = '';
		$grand_total = 0;
		$billList= $this->search_list($fiscal_year, $billTotal ,$grand_total);

		return view('admin.bill_list' ,compact(
			'billList',
			'fiscal_year',
			'billTotal',
			'grand_total',
		));
	}


/*************************************
* 一覧
**************************************/
	public function list(Request $request)
	{
		$fiscal_year = $request->fiscal_year;

		$billTotal = '';
		$grand_total = 0;
		$billList= $this->search_list($fiscal_year ,$billTotal ,$grand_total);

		return view('admin.bill_list' ,compact(
			'billList',
			'fiscal_year',
			'billTotal',
			'grand_total',
		));
	}



/*************************************
* 検索設定
**************************************/
	public function search_list($fiscal_year ,&$billTotal ,&$grand_total)
	{
		$start_date = $fiscal_year . '/07/01';
		$end_date = (($fiscal_year) + 1) . '/06/30';

		$billList = Bill::where('bill_date' ,'>=' ,$start_date)
			->where('bill_date' ,'<=' ,$end_date)
			->join('companies' ,'bills.company_id' ,'companies.id')
			->selectRaw('bills.* ,substring(bills.bill_date,1,7) as fiscal_mon  ,companies.id as company_id  ,companies.name as company_name ,every_start_date ,every_end_date ,monthly_start_date ,monthly_end_date ,yearly_start_date ,yearly_end_date')
			->orderBy('bill_date' ,'desc')
			->get();

		$billTotal = Bill::where('bill_date' ,'>=' ,$start_date)
			->where('bill_date' ,'<=' ,$end_date)
			->selectRaw('substring(bills.bill_date,1,7) as fiscal_mon  ,sum(total_price) as sub_total')
			->groupBy('fiscal_mon')
			->get();

		$grand_total = 0;
		
		foreach ($billTotal as $total) {
			$grand_total += $total->sub_total;
		}

		return $billList;
	}


/*************************************
* 詳細
**************************************/
	public function detail(Request $request)
	{
		$company_id = $request->company_id;
		$end_date = $request->bill_date;
		$start_date = substr($end_date,0,7) . '-01';
		$fiscal_month = substr($end_date,0,4) . '年' . substr($end_date,5,2) . '月' ;
//ddd($request);
		$comp = Company::find($company_id);

		$bill_type = $this->get_bill_type($comp);

		// 都度払い金額設定
		if ( !empty($request->interview_id) ) {
			$int = Interview::find($request->interview_id);
			
			if ( !empty($request->complete_flag) ) {
				$int->complete_flag = '1';
			} else {
				$int->complete_flag = '0';
			}
			if ( !empty($request->job_note) ) {
				$int->job_note = $request->job_note;
			}
			if ( !empty($request->amount) ) {
				$int->amount = $request->amount;
			}
			$int->save();
			
			
			// 合計値計算
			$billTotal = Interview::where('company_id' ,$company_id)
				->where('interviews.interview_type' ,'1' )
				->where('entrance_date' ,'>=' ,$start_date)
				->where('entrance_date' ,'<=' ,$end_date)
				->selectRaw('sum(amount) as sub_total')
				->first();

			$bill = Bill::find($request->bill_id);
			$bill->bill_type = '0';
			$bill->total_price = 0;
			if (!empty($billTotal->sub_total)) $bill->total_price = $billTotal->sub_total;
			$bill->save();
		}

		$intList = Interview::join('companies' ,'interviews.company_id' ,'companies.id')
			->join('users' ,'interviews.user_id' ,'users.id')
			->join('jobs' ,'interviews.job_id' ,'jobs.id')
			->where('interviews.company_id' ,$company_id )
			->where('interviews.interview_type' ,'1' )
			->where('interviews.entrance_date' ,'>=' ,$start_date)
			->where('interviews.entrance_date' ,'<=' ,$end_date)
			->selectRaw('interviews.* ,users.name as user_name ,jobs.name as job_name ,jobs.job_code as job_code')
			->orderBy('entrance_date')
			->get();

		$bill = Bill::where('company_id' ,$company_id)
			->where('bill_date' ,$end_date)
			->selectRaw('bills.*')
			->first();

		return view('admin.bill_detail' ,compact(
			'fiscal_month',
			'comp',
			'intList',
			'bill',
			'bill_type',
		));
	}


/*************************************
* 未請求
**************************************/
	public function no_bill(Request $request)
	{
		$company_id = $request->company_id;

		$comp = Company::find($company_id);

		$bill_type = $this->get_bill_type($comp);

		// 都度払い金額設定 保存ボタンが押された時の処理
		if ( !empty($request->interview_id) ) {
			$int = Interview::find($request->interview_id);
			
			if ( !empty($request->complete_flag) ) {
				$int->complete_flag = '1';
			}
			if ( !empty($request->job_note) ) {
				$int->job_note = $request->job_note;
			}
			if ( !empty($request->amount) ) {
				$int->amount = $request->amount;
			}
			$int->save();
			
			
			// 合計値計算
			$billTotal = Interview::where('company_id' ,$company_id)
				->where('interviews.interview_type' ,'1' )
				->where('entrance_date' ,'>=' ,$start_date)
				->where('entrance_date' ,'<=' ,$end_date)
				->selectRaw('sum(amount) as sub_total')
				->first();

			$bill = Bill::find($request->bill_id);
			$bill->bill_type = '0';
			$bill->total_price = 0;
			if (!empty($billTotal->sub_total)) $bill->total_price = $billTotal->sub_total;
			$bill->save();
		}

		if (empty($comp->every_start_date) ) {
			$intList = '';
		} else {

			$intList = Interview::join('companies' ,'interviews.company_id' ,'companies.id')
				->join('users' ,'interviews.user_id' ,'users.id')
				->join('jobs' ,'interviews.job_id' ,'jobs.id')
				->where('interviews.company_id' ,$company_id )
				->where('interviews.interview_type' ,'1' )
				->where('interviews.entrance_date' ,'>=' ,$comp->every_start_date);
			
			if ($comp->every_end_date != '') {
				$intList = $intList->where('interviews.entrance_date' ,'<=' ,$comp->every_end_date);
			}
		
			$intList = $intList->where('interviews.complete_flag' ,'0')
				->selectRaw('interviews.* ,users.name as user_name ,jobs.name as job_name ,jobs.job_code as job_code')
				->orderBy('entrance_date')
				->get();
		}

		return view('admin.bill_every' ,compact(
//			'fiscal_month',
			'comp',
			'intList',
			'bill_type',
		));
	}


/*************************************
* 請求タイプ取得
**************************************/
	public function get_bill_type($comp)
	{
		$bill_type = '';

		// 都度払い
		if ( !empty($comp->every_end_date) && ($comp->every_end_date < date('Y-m-d')) ) { // 終了日が過去だったら何もしない

		} else {
			if (!empty($comp->every_start_date)  && ($comp->every_start_date <= date('Y-m-d') )) {
				$bill_type = 0;
			}
		}

		// 月々払い
		if ( !empty($comp->monthly_end_date) && $comp->monthly_end_date < date('Y-m-d') ) { // 終了日が過去だったら何もしない

		} else {
			if (!empty($comp->monthly_start_date)  && ($comp->monthly_start_date <= date('Y-m-d') )) {
				$bill_type = 1;
			}
		}

			// 年払い
		if ( !empty($comp->yearly_end_date) && $comp->yearly_end_date < date('Y-m-d') ) { // 終了日が過去だったら何もしない

		} else {
			if (!empty($comp->yearly_start_date)  && ($comp->yearly_start_date <= date('Y-m-d') )) {
				$bill_type = 2;
			}
		}

		return $bill_type;
	}


/*************************************
* 金額変更
**************************************/
	public function change(Request $request)
	{
		$bill = Bill::find($request->bill_id);

		$bill->total_price = $request->total_price;
		$bill->save();

		return redirect()->route('admin.bill.detail', ['company_id' => $bill->company_id ,'bill_date' => $bill->bill_date ]);
	}



/*************************************
* 都度請求一覧
**************************************/
	public function claimEvery()
	{
		$start_date = date('Y/m/01',strtotime("-1 month"));

		$intList = Interview::join('companies' ,'interviews.company_id' ,'companies.id')
			->join('users' ,'interviews.user_id' ,'users.id')
			->join('jobs' ,'interviews.job_id' ,'jobs.id')
			->selectRaw('interviews.* ,users.name as user_name ,jobs.name as job_name ,jobs.job_code as job_code ,companies.name as company_name')
			->where('interviews.interview_type' ,'1' )
			->where('interviews.result_id', '1')
			->where(function($query) use  ($start_date) {
				$query->whereNull('interviews.entrance_date')
				->orWhere('interviews.entrance_date' ,'>=' ,$start_date);
			})
			->orderBy('companies.id')
			->orderBy('entrance_date')
			->get();

		return view('admin.claim_every' ,compact(
			'intList',
		));

	}


/*************************************
* 都度請求保存
**************************************/
	public function claimEveryStore(Request $request)
	{

		$int = Interview::find($request->interview_id);
		
		if ( !empty($request->complete_flag) ) {
			$int->complete_flag = '1';
		} else {
			$int->complete_flag = '0';
		}

		if ( isset($request->job_note) ) {
			$int->job_note = $request->job_note;
		} else {
			$int->job_note = null;
		}
		
		if ( isset($request->amount) ) {
			$int->amount = $request->amount;
		} else {
			$int->amount = null;
		}
		
		$int->save();
		
		return redirect()->route('admin.claim.every');

	}


/*************************************
* 年間／月間請求一覧
**************************************/
	public function claimMonthly()
	{
		$compList = Company::orderBy('name_english')
			->get();

		$start_date = date('Y/m/01',strtotime("-1 month"));

		for ($i = 0; $i < 7; $i++) {
			$title[] = date('Y/m/01',strtotime(($i - 1) . " month"));
			$total['mon' . $i] = 0;
		}
		
		$cnt = 0;
		foreach ($compList as $comp) {
			for ($i = 0; $i < 7; $i++) {
				$temp_date = date('Y/m/01',strtotime(($i - 1) . " month"));
				$mon = ClaimMonth::where('company_id' ,$comp->id)
					->where('claim_date' ,$temp_date)
					->first();
					
				if (empty($mon)) {
					$mon = ClaimMonth::create([
						'company_id'  => $comp->id,
						'claim_date'  => $temp_date,
			        ]);
				}
				
				$compList[$cnt]['mon' . $i] = $mon->amount;
				$total['mon' . $i] = $total['mon' . $i] + $mon->amount;
			}
			$cnt++;
		}

		return view('admin.claim_monthly' ,compact(
			'start_date',
			'title',
			'total',
			'compList',
		));

	}


/*************************************
* 年間／月間求保存
**************************************/
	public function claimMonthlyStore(Request $request)
	{
		$start_date = $request->start_date;

// 今日の0時0分($today)から4日後のUNIX TIMESTAMPを取得する
		
		for ($i = 0; $i < count($request->mon) ; $i++) {

			$mon = ClaimMonth::where('company_id' ,$request->company_id)
				->where('claim_date' ,$start_date)
				->first();
		
			$mon->amount = $request->mon[$i];
			$mon->save();

			$start_date = date("Y-m-d" ,strtotime( $start_date . " +1 month") );
		}

		return redirect()->route('admin.claim.monthly');

	}


}
