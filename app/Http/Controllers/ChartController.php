<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Evaluation;
use App\Models\Company;

class ChartController extends Controller
{
	public function index(Request $request)
	{
		$comp = Company::where('companies.id' ,$request->comp_id)
			->where('open_flag' ,'1')
			->selectRaw('companies.*')
			->first();

		$average = Evaluation::select([\DB::raw(
				'sum(salary_point) as salary_point,'           .
				'sum(welfare_point) as welfare_point,'         .
				'sum(upbring_point) as upbring_point,'         .
				'sum(compliance_point) as compliance_point,'   .
				'sum(motivation_point) as motivation_point,'   .
				'sum(work_life_point) as work_life_point,'     .
				'sum(remote_point) as remote_point,'           .
				'sum(retire_point) as retire_point,'           .

				'count(salary_content) as salary_cnt,'         .
				'count(welfare_content) as welfare_cnt,'       .
				'count(upbring_content) as upbring_cnt,'       .
				'count(compliance_content) as compliance_cnt,' .
				'count(motivation_content) as motivation_cnt,' .
				'count(work_life_content) as work_life_cnt,'   .
				'count(remote_content) as remote_cnt,'         .
				'count(retire_content) as retire_cnt,'         .

				'count(*) as cnt '
			)])
			->where('company_id' ,$comp->id)
			->where('approve_flag' ,'1')
			->first();

		$cat_sel = isset($request->cat_sel) ? $request->cat_sel : null;

		if ($average->cnt > 0) {
			$average->salary_point     = $average->salary_point     / $average->cnt;
			$average->welfare_point    = $average->welfare_point    / $average->cnt;
			$average->upbring_point    = $average->upbring_point    / $average->cnt;
			$average->compliance_point = $average->compliance_point / $average->cnt;
			$average->motivation_point = $average->motivation_point / $average->cnt;
			$average->work_life_point  = $average->work_life_point  / $average->cnt;
			$average->remote_point     = $average->remote_point     / $average->cnt;
			$average->retire_point     = $average->retire_point     / $average->cnt;

			$total_eval = ($average->salary_point
				+ $average->welfare_point
			 	+ $average->upbring_point
			 	+ $average->compliance_point
			 	+ $average->motivation_point
			 	+ $average->work_life_point
			 	+ $average->remote_point
			 	+ $average->retire_point) / 8;

			$total_rate = $total_eval / 5 * 100;


			if (isset($cat_sel) ) {
				$query = Evaluation::leftJoin('users','evaluations.user_id','users.id')
					->where('evaluations.company_id' ,$comp->id)
					->where('evaluations.approve_flag' ,'1');

					if ($cat_sel == '1') {
						$query = $query->whereNotNull('salary_content');
					} elseif ($cat_sel == '2') {
						$query = $query->whereNotNull('welfare_content');
					} elseif ($cat_sel == '3') {
						$query = $query->whereNotNull('upbring_content');
					} elseif ($cat_sel == '4') {
						$query = $query->whereNotNull('compliance_content');
					} elseif ($cat_sel == '5') {
						$query = $query->whereNotNull('motivation_content');
					} elseif ($cat_sel == '6') {
						$query = $query->whereNotNull('work_life_content');
					} elseif ($cat_sel == '7') {
						$query = $query->whereNotNull('remote_content');
					} elseif ($cat_sel == '8') {
						$query = $query->whereNotNull('retire_content');
					}
					
					$evalList = $query->selectRaw('evaluations.*, users.sex as user_sex')
						->orderBy('evaluations.updated_at' ,'desc')
						->paginate(8);

					for ($i = 0; $i < count($evalList); $i++) {
						if (isset($evalList[$i]->user_sex)) {
							$evalList[$i]->sex = $evalList[$i]->user_sex;
						}
						$evalList[$i]->cat_sel = $cat_sel;
					}

			} else {

			}

		} else {
			$total_eval = 0;
			$total_rate = 0;

			$average->salary_point     = 0;
			$average->welfare_point    = 0;
			$average->upbring_point    = 0;
			$average->compliance_point = 0;
			$average->motivation_point = 0;
			$average->work_life_point  = 0;
			$average->remote_point     = 0;
			$average->retire_point     = 0;

			$evalList = null;
		}

		$dummyMsg =  "これはダミーデータです。なので本データを参照することはできません。<br>";
		$dummyMsg .= "もし参照したいのであれば、まず無料のユーザ登録をしてください。<br>";
		$dummyMsg .= "その後、クチコミを登録してもらうと他のクチコミ情報を参照することが可能です。<br>";
		$dummyMsg .= "GaishiITでは、外資企業に特化した応募が可能です。<br>";
		$dummyMsg .= "GaishiIT独自のカジュアル面談の機能もあります。ぜひお試しください。<br>";


		return view('chart',compact(
			'cat_sel',
			'comp',
			'average',
			'evalList',
			'total_eval',
			'total_rate',
			'dummyMsg',
		));
	}


	/**************************************
	 * クチコミ一覧
	 **************************************/
	public function word_list(Request $request)
	{

		$comp_name ='ほげほげ株式会社';
		$wordList = null;
		
		$dummyMsg =  "これはダミーデータです。なので本データを参照することはできません。<br>";
		$dummyMsg .= "もし参照したいのであれば、まず無料のユーザ登録をしてください。<br>";
		$dummyMsg .= "その後、クチコミを登録してもらうと他のクチコミ情報を参照することが可能です。<br>";
		$dummyMsg .= "GaishiITでは、外資企業に特化した応募が可能です。<br>";
		$dummyMsg .= "GaishiIT独自のカジュアル面談の機能もあります。ぜひお試しください。<br>";
		
		return view('word_list',compact(
			'comp_name',
			'wordList',
			'dummyMsg',
		));

	}


}