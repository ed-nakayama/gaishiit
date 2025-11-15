<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

use Illuminate\Http\Request;

use Validator;
use Redirect;
use URL;

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
use App\Models\AgentMailHist;

use App\Mail\AproveToUser;
use App\Mail\AproveToComp;
use App\Mail\RejectToUser;
use App\Mail\AgentToUser;


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
		
		$userQuery = User::JoinSub($subSQL0 , 'user_age' ,'user_age.id', 'users.id')
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
* 候補者詳細情報
**************************************/
	public function detail(Request $request)
	{
		$user_id = $request->user_id;

		$userInfo = User::where('users.id' ,$user_id)
			->first();

		$interviewList = Interview::where('interviews.user_id' ,$user_id)
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
			->where('interviews.user_id' ,$user_id)
//			->whereNull('interviews.entrance_date')
			->get();

		$parent_id = $request->parent_id;

		$agentHist = AgentMailHist::where('user_id' ,$user_id)
			->orderBy('created_at', 'DESC')
			->get();

		return view('admin.user_detail' ,compact(
			'userInfo',
			'interviewList',
			'ownerList',
			'parent_id',
			'agentHist',
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

		$userList = $this->search_can_list($searchHist);

		return view('admin.candidate_list' ,compact(
			'userList',
			'searchHist',
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

		$searchHist->result = $request->result;
		$searchHist->from_age = $request->from_age;
		$searchHist->to_age = $request->to_age;
		$searchHist->current_job = $request->current_job;
		$searchHist->location = $request->location;
		$searchHist->request_cat = $request->request_cat;
		$searchHist->freeword = $request->freeword;

		$searchHist->save();

		$userList = $this->search_can_list($searchHist);

		return view('admin.candidate_list' ,compact(
			'userList',
			'searchHist',
		));
 
	}


/*************************************
* 候補者管理 検索リスト
**************************************/
	public function search_can_list($param)
	{
		$subSQL0 = \DB::table('users')
			->selectRaw("id, TIMESTAMPDIFF(YEAR, users.birthday, CURDATE()) AS age");

		$userQuery = User::JoinSub($subSQL0 , 'user_age' ,'user_age.id', 'users.id')
			->where('aprove_flag', '1')
			->selectRaw("users.*");

		if (!empty($param->result)) $userQuery = $userQuery->where('users.result_id' , $param->result);
		if (!empty($param->from_age)) $userQuery = $userQuery->where('age' ,'>=',  $param->from_age);
		if (!empty($param->to_age)) $userQuery = $userQuery->where('age' ,'<',  $param->to_age + 10);
		if (!empty($param->current_job)) $userQuery = $userQuery->whereIn('users.job_cats' , ["{$param->current_job}"]);
		if (!empty($param->location)) $userQuery = $userQuery->where('users.request_location', 'like', "%{$param->location}%");
		if (!empty($param->request_cat)) $userQuery = $userQuery->where('users.job_cats', 'like', "%{$param->request_cat}%");

		if (!empty($param->freeword)) {
			$freeword = $param->freeword;

			$userQuery = $userQuery
				->where(function($query) use ($freeword) {
					$query->where('users.graduation' , 'like', "%{$freeword}%")
					->orWhere('users.name' , 'like', "%{$freeword}%")
					->orWhere('users.email' , 'like', "%{$freeword}%")
					->orWhere('users.company' , 'like', "%{$freeword}%")
					->orWhere('users.job_content' , 'like', "%{$freeword}%")
					;
				});
		}
		
		$userList = $userQuery->orderBy('created_at' ,'desc')->paginate(20);

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


/*************************************
* メール送信
**************************************/
	public function send( Request $request)
	{
		$validator = Validator::make($request->all(), [
			'parent_id' => ['nullable','string'],
			'user_id'   => ['required','string'],
			'from_mail' => ['required','string','email'],
			'to_mail'   => ['required','string','email'],
			'cc_mail'   => ['nullable','string','email'],
			'title'     => ['required','string'],
			'content'   => ['required','string'],
		]);

		$parent_id = $request->parent_id;
		$user_id = $request->user_id;
		$content = $request->content;
		
		if($validator->fails()) {
			return Redirect::to(URL::previous() . "?parent_id={$parent_id}&user_id={$user_id}")->withInput()->with('errors', $validator->messages());
		}

		$parent_id = $request->parent_id;
		$user_id   = $request->user_id;
		$from_mail = $request->from_mail;
		$to_mail   = $request->to_mail;
		$cc_mail   = !empty($request->cc_mail) ? $request->cc_mail : null;
		$title     = $request->title;
		$content   = $request->content;

		Mail::send(new AgentToUser($from_mail, $to_mail, $cc_mail, $title, $content));

		$loginUser = Auth::user();

		$agentMailHist = AgentMailHist::create([
			'user_id'  => $user_id,
			'agent_id' => $loginUser->id,
			'content'  => $content,
		]);

		return redirect()->route('admin.user.detail', ['parent_id'=>$parent_id, 'user_id'=>$user_id] );
	}


/*************************************
* リファラ一覧
**************************************/
	public function referer(Request $request)
	{
		$userList = USER::orderBy('created_at' ,'desc');

		if (!empty($request->referer)) {
			$referer = $request->referer;
			$userList =$userList->where('referer', 'like', "%{$referer}%");
		} else {
			$referer = '';
		}
	
		$userList = $userList->paginate(20);

		return view('admin.referer_list' ,compact(
			'userList',
			'referer',
		));
	}


/*************************************
* ユーザ基本情報 PDF出力
**************************************/
	public function userBasePdf(Request $request)
	{
		$loginUser = Auth::user();

		$userInfo = "";
		if ( !empty($request->user_id) ) {
			$userInfo = User::where('users.id' ,$request->user_id)
				->first();
		}

		$pdf = \SnappyPdf::loadView('pdf_templates.user_base_open',
			['userInfo' => $userInfo],
		)
			->setPaper('A4')
			->setOption('disable-smart-shrinking', true);
		
		return $pdf->download('user_basic_open.pdf');
	}


}
