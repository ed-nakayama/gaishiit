<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\Controller;

use App\Models\Company;
use App\Models\Evaluation;
use App\Models\Ranking;


class EvalController extends Controller
{
	public function __construct()
	{
		$this->middleware('auth:user');
	}


/*************************************
* 初期表示
**************************************/
	public function index(Request $request)
	{
		$validated = $request->validate([
			'comp_id' => ['required', 'string'],
		]);

		$loginUser = Auth::user();

		$comp = Company::find($request->comp_id);

		$eval = Evaluation::where('company_id' ,$request->comp_id)
			->where('user_id' ,$loginUser->id)
			->first();

		if (!isset($eval)) $eval = new Evaluation();

		if ($eval->approve_flag == '8') {
			return view('user.eval_ref', compact(
				'comp',
				'eval',
			));

		} else {
			return view('user.eval_regist', compact(
				'comp',
				'eval',
			));
		}
	}


/*************************************
* 個人設定更新
**************************************/
	public function store(Request $request)
	{
		$validated = $request->validate([
			'comp_id'       => ['required', 'string'],
			'emp_status'    => ['required'],
			'tenure_status' => ['required'],
			'join_status'   => ['required'],
			'join_year'     => ['required'],
			'retire_year'   => ['required','after_or_equal:join_year'],
			'occupation'    => ['required', 'string'],
		]);

		$approve_flag = '0'; // 編集中
		$this->update($request ,$approve_flag);

		return redirect()->route('user.eval.regist', 
			[
			'comp_id' => $request->input('comp_id'),
			])
			->with('status', 'success-store');
	}


/*************************************
* クチコミ設定確認
**************************************/
	public function confirm(Request $request)
	{
		$validated = $request->validate([
			'comp_id'       => ['required', 'string'],
			'emp_status'    => ['required'],
			'tenure_status' => ['required'],
			'join_status'   => ['required'],
			'join_year'     => ['required'],
			'retire_year'   => ['required'],
			'occupation'    => ['required', 'string'],
			'retire_year'   => ['required'],
		]);

		if (isset($request->next)) {
			$validated = $request->validate([
				'input_len'  => ['required', 'numeric', 'min:500'],
			]);
		}

		$loginUser = Auth::user();

		$comp = Company::find($request->comp_id);

		$eval = new Evaluation();

		$eval->emp_status         = $request->emp_status;
		$eval->tenure_status      = $request->tenure_status;
		$eval->join_status        = $request->join_status;
		$eval->join_year          = $request->join_year;
		$eval->retire_year        = $request->retire_year;
		$eval->occupation         = $request->occupation;
		$eval->ote_income         = $request->ote_income;
		$eval->salary_point       = $request->salary_point;
		$eval->salary_content     = $request->salary_content;
		$eval->welfare_point      = $request->welfare_point;
		$eval->welfare_content    = $request->welfare_content;
		$eval->upbring_point      = $request->upbring_point;
		$eval->upbring_content    = $request->upbring_content;
		$eval->compliance_point   = $request->compliance_point;
		$eval->compliance_content = $request->compliance_content;
		$eval->motivation_point   = $request->motivation_point;
		$eval->motivation_content = $request->motivation_content;
		$eval->work_life_point    = $request->work_life_point;
		$eval->work_life_content  = $request->work_life_content;
		$eval->remote_point       = $request->remote_point;
		$eval->remote_content     = $request->remote_content;
		$eval->retire_point       = $request->retire_point;
		$eval->retire_content     = $request->retire_content;

		return view('user.eval_confirm', compact(
			'comp',
			'eval',
		));
	}


/*************************************
* クチコミ設定送信
**************************************/
	public function send(Request $request)
	{
		$approve_flag = 1; // 申請中
		$this->update($request ,$approve_flag);

		return redirect()->route('user.eval.complete');
	}


/*************************************
* クチコミデータ更新
**************************************/
	public function update($request ,$approve_flag)
	{

		$loginUser = Auth::user();

		$comp = Company::find($request->comp_id);

		$eval = Evaluation::where('company_id' ,$request->comp_id)
			->where('user_id' ,$loginUser->id)
			->first();

		if (!isset($eval)) {
			$eval = Evaluation::create([
				'company_id'         => $request->comp_id,
				'user_id'            => $loginUser->id,
				'emp_status'         => $request->emp_status,
				'tenure_status'      => $request->tenure_status,
				'join_status'        => $request->join_status,
				'join_year'          => $request->join_year,
				'retire_year'        => $request->retire_year,
				'occupation'         => $request->occupation,
				'ote_income'         => $request->ote_income,
				'salary_point'       => $request->salary_point,
				'salary_content'     => $request->salary_content,
				'welfare_point'      => $request->welfare_point,
				'welfare_content'    => $request->welfare_content,
				'upbring_point'      => $request->upbring_point,
				'upbring_content'    => $request->upbring_content,
				'compliance_point'   => $request->compliance_point,
				'compliance_content' => $request->compliance_content,
				'motivation_point'   => $request->motivation_point,
				'motivation_content' => $request->motivation_content,
				'work_life_point'    => $request->work_life_point,
				'work_life_content'  => $request->work_life_content,
				'remote_point'       => $request->remote_point,
				'remote_content'     => $request->remote_content,
				'retire_point'       => $request->retire_point,
				'retire_content'     => $request->retire_content,
				'approve_flag'       => $approve_flag,
			]);

		} else {
			$eval->emp_status         = $request->emp_status;
			$eval->tenure_status      = $request->tenure_status;
			$eval->join_status        = $request->join_status;
			$eval->join_year          = $request->join_year;
			$eval->retire_year        = $request->retire_year;
			$eval->occupation         = $request->occupation;
			$eval->ote_income         = $request->ote_income;
			$eval->salary_point       = $request->salary_point;
			$eval->salary_content     = $request->salary_content;
			$eval->welfare_point      = $request->welfare_point;
			$eval->welfare_content    = $request->welfare_content;
			$eval->upbring_point      = $request->upbring_point;
			$eval->upbring_content    = $request->upbring_content;
			$eval->compliance_point   = $request->compliance_point;
			$eval->compliance_content = $request->compliance_content;
			$eval->motivation_point   = $request->motivation_point;
			$eval->motivation_content = $request->motivation_content;
			$eval->work_life_point    = $request->work_life_point;
			$eval->work_life_content  = $request->work_life_content;
			$eval->remote_point       = $request->remote_point;
			$eval->remote_content     = $request->remote_content;
			$eval->retire_point       = $request->retire_point;
			$eval->retire_content     = $request->retire_content;
			$eval->approve_flag       = $approve_flag;

			$eval->save();
		}

	}


/*************************************
* クチコミ設定完了
**************************************/
	public function complete()
	{
		return view('user.eval_complete');
	}


}
