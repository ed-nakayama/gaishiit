<?php

namespace App\Http\Controllers\Comp;

use App\Http\Controllers\InterviewController;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\ConstCatDetail;
use App\Models\Company;
use App\Models\CompMember;
use App\Models\Unit;
use App\Models\Job;
use App\Models\JobCat;
use App\Models\JobCatDetail;
use App\Models\BusinessCatDetail;
use App\Models\User;
use App\Models\Interview;
use App\Models\InterviewMessage;
use App\Models\InterviewMsgStatus;
use App\Models\EventHead;
use App\Models\EventMessage;
use App\Models\EventMsgStatus;
use App\Models\ConstLocation;
use App\Models\MaskMessage;
use App\Models\CompInmail;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log; 
use Storage;
use Validator;


use App\Mail\MessageToUser;
use App\Mail\CompAproveToUser;
use App\Mail\CompRejectToUser;


class CompInterviewController extends InterviewController
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
	}


/*************************************
* カジュアルリスト
**************************************/
	public function casualList()
	{
		$loginUser = Auth::user();

 		$query =  Interview::query();

		$interviewList = $query->join('users','interviews.user_id','=','users.id')
			->leftJoin('comp_members','interviews.member_id','=','comp_members.id')
			->leftJoin('interview_msg_statuses', function ($join) use ($loginUser) {
                $join->on('interview_msg_statuses.interview_id','=','interviews.id')
					->where('interview_msg_statuses.reader_id' ,$loginUser->id);
			})
			->leftJoin('companies','interviews.company_id','=','companies.id')
			->leftJoin('units','interviews.unit_id','=','units.id')
			->leftJoin('jobs','interviews.job_id','=','jobs.id')

			->addSelect(['last_update' => InterviewMessage::select('interview_messages.updated_at')
			    ->whereColumn('interview_messages.interview_id', 'interviews.id')
			    ->orderBy('interview_messages.updated_at', 'desc')
			    ->take(1)
			])

			->selectRaw('interviews.*,' .
						'interview_msg_statuses.read_flag,' .
						'users.name as user_name, users.nick_name as user_nick_name,' .
						'comp_members.name as member_name,' .
						'jobs.name as job_name,' .
						'units.name as unit_name,' .
						'companies.name as company_name')
			->where('interviews.company_id' ,$loginUser->company_id)


			->where(function($query) use ($loginUser) {
				$query
					->where(function($query) use ($loginUser) {
						$query->where('interviews.interview_kind'  ,'0')
							->where('companies.person', 'like' ,"%$loginUser->id%");
					})
					->orWhere(function($query) use ($loginUser) {
						$query->where('interviews.interview_kind'  ,'1')
							->where('units.person', 'like' ,"%$loginUser->id%");
					})
					->orWhere(function($query) use ($loginUser) {
						$query->where('interviews.interview_kind'  ,'2')
							->where('jobs.person', 'like'  ,"%$loginUser->id%");
					})
				;
			})

			->where('interview_type' ,0)
			->orderBy('last_update' ,'desc')
			->paginate(10);

		$i = 0;
		foreach ($interviewList as $cas) {
	 		$msg = InterviewMessage::leftJoin('comp_members','interview_messages.member_id','=','comp_members.id')
	 			->leftJoin('users','interview_messages.user_id','=','users.id')
	 			->selectRaw('content ,users.name as user_name, comp_members.name as member_name')
	 			->where('interview_id', $cas->id)
	 			->orderBy('interview_messages.id', 'desc')
	 			->first();
	 		
	 		if (!empty($msg->user_name)) {
				if ($cas->aprove_flag == '1') {
 	 				$interviewList[$i]->last_sender = $msg->user_name;
				} else {
 	 				$interviewList[$i]->last_sender = $cas->user_nick_name;
				}
 	 		} else {
 	 			$interviewList[$i]->last_sender = $msg->member_name;
 			}
 	 		$interviewList[$i++]->last_msg = $msg->content;
		}
		
		
		return view('comp.casual_msg_list' ,compact('interviewList'));
	}


/*************************************
* 正式リスト
**************************************/
	public function formalList()
	{
		$loginUser = Auth::user();

 		$query =  Interview::query();

		$interviewList = $query->join('users','interviews.user_id','=','users.id')
			->leftJoin('comp_members','interviews.member_id','=','comp_members.id')
			->leftJoin('interview_msg_statuses', function ($join) use ($loginUser) {
                $join->on('interview_msg_statuses.interview_id','=','interviews.id')
					->where('interview_msg_statuses.reader_id' ,$loginUser->id);
			})
			->leftJoin('companies','interviews.company_id','=','companies.id')
			->leftJoin('units','interviews.unit_id','=','units.id')
			->Join('jobs','interviews.job_id','=','jobs.id')

			->addSelect(['last_update' => InterviewMessage::select('interview_messages.updated_at')
			    ->whereColumn('interview_messages.interview_id', 'interviews.id')
			    ->orderBy('interview_messages.updated_at', 'desc')
			    ->take(1)
			])

			->selectRaw('interviews.*,' .
						'interview_msg_statuses.read_flag,' .
						'users.name as user_name, users.nick_name as user_nick_name,' .
						'comp_members.name as member_name,' .
						'jobs.name as job_name,' .
						'units.name as unit_name,' .
						'companies.name as company_name')
			->where('jobs.person' , 'like' ,"%$loginUser->id%")
			->where('interviews.company_id' ,$loginUser->company_id)
			->where('interview_type' ,1)
//			->orderBy('id' ,'desc')
			->orderBy('last_update' ,'desc')
			->paginate(10);

		$i = 0;
		foreach ($interviewList as $cas) {
	 		$msg = InterviewMessage::leftJoin('comp_members','interview_messages.member_id','=','comp_members.id')
	 			->leftJoin('users','interview_messages.user_id','=','users.id')
	 			->selectRaw('content ,users.name as user_name, comp_members.name as member_name')
	 			->where('interview_id', $cas->id)
	 			->orderBy('interview_messages.id', 'desc')
	 			->first();
	 		
	 		if (!empty($msg->user_name)) {
 	 			$interviewList[$i]->last_sender = $msg['user_name'];
 	 		} else {
 	 			$interviewList[$i]->last_sender = $msg['member_name'];
 			}
 	 		$interviewList[$i++]->last_msg = $msg['content'];
		}
		
		
		return view('comp.formal_msg_list' ,compact('interviewList'));
	}


/*************************************
* イベントリスト
**************************************/
	public function eventList()
	{
		$loginUser = Auth::user();

 		$query =  Interview::query();

		$eventList = $query->join('events','interviews.event_id','=' ,'events.id')
			->leftJoin('users','interviews.user_id','=','users.id')
			->leftJoin('comp_members','interviews.member_id','=','comp_members.id')
			->leftJoin('interview_msg_statuses', function ($join) use ($loginUser) {
                $join->on('interview_msg_statuses.interview_id','=','interviews.id')
					->where('interview_msg_statuses.reader_id' ,$loginUser->id);
			})
			->leftJoin('companies','events.company_id','=','companies.id')
			->leftJoin('units','events.unit_id','=','units.id')

			->addSelect(['last_update' => InterviewMessage::select('interview_messages.updated_at')
			    ->whereColumn('interview_messages.interview_id', 'interviews.id')
			    ->orderBy('interview_messages.updated_at', 'desc')
			    ->take(1)
			])

			->selectRaw('interviews.*,' .
						'interview_msg_statuses.read_flag,' .
						'users.name as user_name, users.nick_name as user_nick_name,' .
						'comp_members.name as member_name,' .
						'units.name as unit_name,' .
						'companies.name as company_name')
			->where('events.person' , 'like' ,"%$loginUser->id%")
			->where('interviews.company_id' ,$loginUser->company_id)
			->orderBy('last_update' ,'desc')
			->paginate(10);
//ddd($eventList);
		$i = 0;
		foreach ($eventList as $cas) {
	 		$msg = InterviewMessage::leftJoin('comp_members','interview_messages.member_id','=','comp_members.id')
	 			->leftJoin('users','interview_messages.user_id','=','users.id')
	 			->selectRaw('content ,users.name as user_name ,users.nick_name as nick_name, comp_members.name as member_name')
	 			->where('interview_id', $cas->id)
	 			->orderBy('interview_messages.id', 'desc')
	 			->first();
	 		
	 		if (!empty($msg->user_name)) {
				if ($cas->aprove_flag == '1') {
 	 				$eventList[$i]->last_sender = $msg->user_name;
				} else {
 	 				$eventList[$i]->last_sender = $msg->nick_name;
				}
 	 		} else {
 	 			$eventList[$i]->last_sender = $msg->member_name;
 			}
 	 		$eventList[$i++]->last_msg = $msg->content;
		}
		
		
		return view('comp.event_msg_list' ,compact('eventList'));
	}


/*************************************
* ユーザ情報取得
**************************************/
	public function get_user($user_id)
	{
		$userInfo = User::leftJoin('const_englishes as eng','users.english','=','eng.id')
			->leftJoin('const_englishes as jpn','users.japanese','=','jpn.id')
			->leftJoin('const_prefs','users.pref' ,'=' ,'const_prefs.id')
			->selectRaw('users.* ,eng.name as english_name ,jpn.name as japanese_name  ,const_prefs.name as pref_name ')
			->where('users.id' ,$user_id)
			->first();

		// 1年以内にメッセージのやり取りがあれば氏名も表示
		$pre_date = date("Y-m-d",strtotime("-1 year"));
	
		$int_count = Interview::where('interviews.user_id', $user_id)
			->where('aprove_flag', '1')
			->where('updated_at', '>' , $pre_date)
			->count();

		$userInfo['open_flag'] = '0';
		if ($int_count > 0) $userInfo['open_flag'] = '1';


		// 勤務地の取得
		$locName = array();
		$locs = explode(",", $userInfo->request_location);
		$locList = ConstLocation::select('name')->whereIn('id' ,$locs)->get();
		foreach ($locList as $loc) {
			$locName[] = $loc->name;
		}
		$userInfo['location_name'] = join(" / " ,$locName);


		// 職種名の取得
		$catName = array();
		$cats = explode(",", $userInfo->job_cats);
		$catList = JobCat::select('name')->whereIn('id' ,$cats)->get();
		foreach ($catList as $cat) {
			$catName[] = $cat->name;
		}
		$userInfo['jobcat_name'] = join(" / ",$catName);


		// 業種名の取得
		$catName = array();
		$cats = explode(",", $userInfo->business_cats);
		$catList = BusinessCatDetail::select('name')->whereIn('id' ,$cats)->get();
		foreach ($catList as $cat) {
			$catName[] = $cat->name;
		}
		$userInfo['buscat_name'] = join(" / ",$catName);

		return $userInfo;
	}



/*************************************
* インタビューフロー
**************************************/
	public function interviewFlow(Request $request)
	{
		
		$loginUser = Auth::user();

		// 既読フラグセット
		InterviewMsgStatus::updateOrCreate(
			['interview_id' => $request->interview_id, 'reader_id' => $loginUser->id ],
			['reader_type' => 'C', 'read_flag' => '1']
		);

		$interview = Job::rightJoin('interviews','interviews.job_id','=','jobs.id')
			->leftJoin('companies','interviews.company_id','=','companies.id')
			->leftJoin('units','interviews.unit_id','=','units.id')
			->leftJoin('events','interviews.event_id','=','events.id')
			->selectRaw('interviews.*,' .
						'jobs.* ,' .
						'interviews.id as interview_id,' .
						'interviews.created_at as interviews_created_at,' .
						'units.name as unit_name,' .
						'events.name as event_name ,' .
						'companies.name as company_name, companies.logo_file as company_logo ')
			->where('interviews.id' ,$request->interview_id)
			->where('interviews.company_id' ,$loginUser->company_id)
			->first();

		if (!isset($interview)) {
			abort(404);
		}

		$msgList = InterviewMessage::leftJoin('users','interview_messages.user_id','=','users.id')
			->leftJoin('comp_members','interview_messages.member_id','=','comp_members.id')
			->selectRaw('interview_messages.*,' .
						'users.name as user_name ,users.nick_name as user_nick_name,' .
						'comp_members.name as member_name')
			->where('interview_messages.interview_id' , $request->interview_id)
			->orderBy('interview_messages.id')
			->get();

		$userInfo = $this->get_user($interview->user_id);

		$maskMsg = MaskMessage::where('member_id' ,$loginUser->id)
			->where('interview_type' ,$interview->interview_type)
			->get();
		
		if (empty($maskMsg[0])) {
			$content = "この度はご応募頂きありがとうございます。\n" .
						"早速ではございますが、担当者との面接をご案内させて頂きたいと思いますので、\n" .
						"来週以降でご都合のよい候補日時を3つお知らせ頂けますでしょうか。\n" .
						"ご連絡をお待ちしておりますので、どうぞよろしくお願い致します。";
			
        	MaskMessage::create([
            	'member_id' => $loginUser->id,
            	'interview_type' => $interview->interview_type,
            	'title' => "サンプル定型文",
            	'content' => $content,
        	]);

			$maskMsg = MaskMessage::where('member_id' ,$loginUser->id)
				->where('interview_type' ,$interview->interview_type)
				->get();
		}


		return view('comp.interview_flow' ,compact(
			'interview',
			'msgList',
			'userInfo',
			'maskMsg',
			));
	}


/*************************************
* インタビューフローPOST
**************************************/
	public function interviewFlowPost(Request $request)
	{
		$loginUser = Auth::user();

		$interview = Interview::find($request->interview_id);

			$validatedData = $request->validate([
				'content'     => ['required' ,'string'],
			]);

		if ($interview->interview_type == '0' || $interview->interview_type == '1') {
			if (isset($request->stage)) $interview->stage_id = $request->stage;
			if (isset($request->status)) $interview->status_id = $request->status;
			if (isset($request->result)) $interview->result_id = $request->result;
			if (isset($request->interview_date)) $interview->interview_date = $request->interview_date;
		
			$interview->save();
		}

		InterviewMessage::create([
            'interview_id' => $request->interview_id,
            'member_id'    => $loginUser->id,
            'content'      => $request->content,
        ]);
        

		$user = User::find($interview->user_id);
		$comp = Company::find($interview->company_id);
		
		$person = array();
        
        // 未読フラグに設定
        if ($interview->intervew_type == '1') { // 正式応募
			$job = Job::find($interview->job_id);
			$person = explode(",", $job['person']);

			if ($user->formal_mail_flag == '1')  {
				Mail::send(new MessageToUser($user ,$comp ,$interview));
				$user->formal_mail_date = date("Y-m-d H:i:s");
				$user->save();
			}

        } else if ($interview->intervew_type == '2') { // イベント
	        if ($interview->interview_kind == '1') { // 部署
				$unit = Unit::find($interview->unit_id);
				$person = explode(",", $unit['person']);

	        } else { // 企業
				$comp = Company::find($interview->company_id);
				$person = explode(",", $comp['person']);
			}

			if ($user->event_mail_flag == '1')  {
				Mail::send(new MessageToUser($user ,$comp ,$interview));
				$user->event_mail_date = date("Y-m-d H:i:s");
				$user->save();
			}
			
        } else { // カジュアル
	        if ($interview->interview_kind == '1') { // 部署
				$unit = Unit::find($interview->unit_id);
				$person = explode(",", $unit['person']);

	        } elseif ($interview->interview_kind == '2') { // ジョブ
				$job = Job::find($interview->job_id);
				$person = explode(",", $job['person']);

	        } else { // 企業
				$person = explode(",", $comp['person']);
			}

			if ($user->casual_mail_flag == '1')  {
				Mail::send(new MessageToUser($user ,$comp ,$interview));
				$user->casual_mail_date = date("Y-m-d H:i:s");
				$user->save();
			}
        }

		$cnt = count($person);
		for ($i = 0; $i < $cnt; $i++) {
			if ($person[$i] == $loginUser->id) {
				InterviewMsgStatus::updateOrCreate(
					['interview_id' => $interview->id, 'reader_id' => $person[$i] ],
					['reader_type' => 'C', 'read_flag' => '1']
				);

			} else {
				InterviewMsgStatus::updateOrCreate(
					['interview_id' => $interview->id, 'reader_id' => $person[$i] ],
					['reader_type' => 'C', 'read_flag' => '0']
				);
			}
		}

		InterviewMsgStatus::updateOrCreate(
			['interview_id' => $interview->id, 'reader_id' => $interview->user_id ],
			['reader_type' => 'U', 'read_flag' => '0']
		);

		return redirect('comp/interview/flow?interview_id=' . $request->interview_id);
	}


/*************************************
* インタビュー承認
**************************************/
	public function aprove(Request $request)
	{

		$loginUser = Auth::user();

		$interview = Interview::leftJoin('companies','interviews.company_id','=','companies.id')
			->leftJoin('units','interviews.unit_id','=','units.id')
			->leftJoin('jobs','interviews.job_id','=','jobs.id')
			->leftJoin('events','interviews.event_id','=','events.id')
			->selectRaw('interviews.*,' .
						'jobs.name as job_name,' .
						'units.name as unit_name,' .
						'companies.name as company_name,'.
						'events.name as event_name')
			->where('interviews.id' ,$request->interview_id)
			->where('interviews.company_id' , $loginUser->company_id)
			->where('interviews.aprove_flag' , '0')
			->first();

		if (!isset($interview)) {
			abort(404);
		}

		$interview->member_id = $loginUser->id;
		$interview->aprove_flag = $request->aprove_flag;
		$interview->aprove_date = date("Y-m-d H:i:s");
		$interview->save();

		$user = User::find($interview->user_id);

		$content ="";

		if ($interview->interview_type == '0') {
			if ($request->aprove_flag == '1') { // 承認のメール送信
				$content ="カジュアル面談へのお申込みが承認されました。";
				if ($user->casual_mail_flag == '1') {
					Mail::send(new CompAproveToUser($user, $interview));
					$user->casual_mail_date = date("Y-m-d H:i:s");
					$user->save();
				}
				
			} elseif  ($request->aprove_flag == '2') {
				$content ="カジュアル面談へのお申込みが否認されました。";
				if ($user->casual_mail_flag == '1') {
					Mail::send(new CompRejectToUser($user, $interview));
					$user->casual_mail_date = date("Y-m-d H:i:s");
					$user->save();
				}
			}
			
		}

		if ($interview->interview_type == '1') {
			if ($request->aprove_flag == '1') { // 承認のメール送信
				$content ="正式応募へのお申込みが承認されました。";
				if ($user->formal_mail_flag == '1') {
					Mail::send(new CompAproveToUser($user, $interview));
					$user->formal_mail_date = date("Y-m-d H:i:s");
					$user->save();
				}
				
			} elseif  ($request->aprove_flag == '2') {
				$content ="正式応募へのお申込みが否認されました。";
				if ($user->formal_mail_flag == '1') {
					Mail::send(new CompRejectToUser($user, $interview));
					$user->formal_mail_date = date("Y-m-d H:i:s");
					$user->save();
				}
			}
		}

		if ($interview->interview_type == '2') {
			if ($request->aprove_flag == '1') { // 承認のメール送信
				$content ="イベントへのお申込みが承認されました。";
				if ($user->event_mail_flag == '1') {
					Mail::send(new CompAproveToUser($user, $interview));
					$user->event_mail_date = date("Y-m-d H:i:s");
					$user->save();
				}
				
			} elseif  ($request->aprove_flag == '2') {
				$content ="イベントへのお申込みが否認されました。";
				if ($user->event_mail_flag == '1') {
					Mail::send(new CompRejectToUser($user ,$interview));
					$user->event_mail_date = date("Y-m-d H:i:s");
					$user->save();
				}
			}
		}


		InterviewMessage::create([
            'interview_id' => $interview->id,
            'member_id'    => $loginUser->id,
            'content'      => $content,
        ]);

		InterviewMsgStatus::updateOrCreate(
			['interview_id' => $interview->id, 'reader_id' => $interview->user_id ],
			['reader_type' => 'U', 'read_flag' => '0']
		);

		return redirect('comp/interview/flow?interview_id=' . $request->interview_id);
	}


	/*************************************
	* インタビューフロー
	**************************************/
	public function interviewMask(Request $request)
	{
		$loginUser = Auth::user();

		if ( empty($request->select) ) { // 新規作成

        	MaskMessage::create([
            	'member_id' => $loginUser->id,
            	'interview_type' => $request->interview_type,
            	'title' => $request->title,
            	'content' => $request->content,
        	]);

		} else {						// 変更
        	$mask = MaskMessage::find($request->select);
        	$mask->title = $request->title;
        	$mask->content = $request->content;
			$mask->save();
		}

		return redirect('comp/interview/flow?interview_id=' . $request->interview_id);
	}


	/*************************************
	* インタビューリクエスト
	**************************************/
	public function interview_request(Request $request)
	{
		$loginUser = Auth::user();

		$int_type = $request->int_type;

		$comp = Company::find($loginUser->company_id);
		$unitList = Unit::whereIn('units.person',  [$loginUser->id])->get();
		$jobList = Job::whereIn('jobs.person' , [$loginUser->id])->get();

		$userDetail = User::where('id' , $request->user_id)
			->where(function($query) use  ($loginUser) {
				$query->whereNull('users.no_company')
				->orWhere('users.no_company','not LIKE' , "%{$loginUser->company_id}%");
			})
			->first();

		if (!isset($userDetail)) {
			abort(404);
		}

		// 1年以内にメッセージのやり取りがあれば氏名も表示
		$pre_date = date("Y-m-d",strtotime("-1 year"));
	
		$int_count = Interview::where('interviews.user_id', $request->user_id)
			->where('aprove_flag', '1')
			->where('updated_at', '>' , $pre_date)
			->count();

		$userDetail['open_flag'] = '0';
		if ($int_count > 0) $userDetail['open_flag'] = '1';

		$userComp = Interview::Join('companies','interviews.company_id','=','companies.id')
			->Join('const_results' ,'interviews.result_id' ,'=' , 'const_results.id')
			->selectRaw('const_results.name as result_name')
			->where('interviews.company_id' ,$loginUser->company_id)
			->where('interviews.user_id' ,$request->user_id)
			->where('interviews.interview_type' ,'1')
			->orderBy('interviews.result_id')
			->first();

		$yearMon = date("Y-m-01");

		$inmail = CompInmail::where('company_id' , $comp->id)
			->where('year_month' ,$yearMon)
			->first();

		if (empty($inmail)) {
			$inmail = CompInmail::create([
				'company_id' => $comp->id,
				'year_month' => $yearMon,
				'inmail_formal' => 0,
				'inmail_casual' => 0,
			]);
		}

		return view('comp.interview_request' ,compact(
			'int_type',
			'unitList',
			'jobList',
			'userDetail',
			'userComp',
			));
	}


	/*************************************
	* インタビューリクエスト送信
	**************************************/
	public function interview_request_store(Request $request)
	{
		if ($request->int_type == '1') {
			$validatedData = $request->validate([
				'job_id' => ['required'],
				'msg'    => ['required'],
			]);
		} else {
			$validatedData = $request->validate([
				'msg'    => ['required'],
				'unit_id' => ['required_without:job_id'],
				'job_id'  => ['required_without:unit_id'],
			]);
		}
		
		$loginUser = Auth::user();

		$int_type = $request->int_type;
		
		if (!empty($request->job_id) ) {
			$int_kind = 2;
		} elseif (!empty($request->unit_id) ) {
			$int_kind = 1;
		} else {
			$int_kind = 0;
		}

		$job_id = $request->job_id;
		$unit_id = $request->unit_id;

		if (!empty($job_id)) {
			$job = Job::find($job_id);

			if (empty($unit_id)) {
				$unit_id = $job->unit_id;
			}
		}
	
		$content = $request->msg;
/*
		if ($request->int_type == '0') {
			$content = "カジュアル面談を依頼します。";
		} else if ($request->int_type == '1') {
			$content = "正式応募を依頼します。";
		} else {
			$content = "";
		}
*/
		$interview = Interview::create([
            'interview_type' => $int_type,
            'interview_kind' => $int_kind,
            'user_id'        => $request->user_id,
            'company_id'     => $loginUser->company_id,
            'unit_id'        => $unit_id,
            'job_id'         => $job_id,
            'propose_type'   => '1',
            'member_id'      => $loginUser->id,
            'last_update_id' => $loginUser->id,
        ]);

		InterviewMessage::create([
            'interview_id' => $interview->id,
            'member_id'    => $loginUser->id,
            'content'      => $content,
        ]);

		$user = User::find($interview->user_id);
		$comp = Company::find($interview->company_id);

		$person = array();
        
        // 未読フラグに設定
        if ($interview->interview_type == '1') { // 正式応募
			$job = Job::find($interview->job_id);
			$person = explode(",", $job['person']);

			if ($user->formal_mail_flag == '1')  {
				Mail::send(new MessageToUser($user ,$comp ,$interview));
				$user->formal_mail_date = date("Y-m-d H:i:s");
				$user->save();
			}

        } else { // カジュアル
	        if ($interview->interview_kind == '1') { // 部署
				$unit = Unit::find($interview->unit_id);
				$person = explode(",", $unit['person']);

	        } elseif ($interview->interview_kind == '2') { // ジョブ
				$job = Job::find($interview->job_id);
				$person = explode(",", $job['person']);

	        } else { // 企業
				$person = explode(",", $comp['person']);
			}

			if ($user->casual_mail_flag == '1')  {
				Mail::send(new MessageToUser($user ,$comp ,$interview));
				$user->casual_mail_date = date("Y-m-d H:i:s");
				$user->save();
			}
        }

		$cnt = count($person);
		for ($i = 0; $i < $cnt; $i++) {
			if ($person[$i] == $loginUser->id) {
				InterviewMsgStatus::updateOrCreate(
					['interview_id' => $interview->id, 'reader_id' => $person[$i] ],
					['reader_type' => 'C', 'read_flag' => '1']
				);

			} else {
				$member = CompMember::find($person[$i]);
				
				if (!empty($member)) {
					InterviewMsgStatus::updateOrCreate(
						['interview_id' => $interview->id, 'reader_id' => $person[$i] ],
						['reader_type' => 'C', 'read_flag' => '0']
					);
				}
			}
		}

		InterviewMsgStatus::updateOrCreate(
			['interview_id' => $interview->id, 'reader_id' => $interview->user_id ],
			['reader_type' => 'U', 'read_flag' => '0']
		);

		$yearMon = date("Y-m-01");

		$inmail = CompInmail::where('company_id' , $comp->id)
			->where('year_month' ,$yearMon)
			->first();
		
		if (empty($inmail)) {
			$inmail = CompInmail::create([
				'company_id' => $comp->id,
				'year_month' => $yearMon,
				'inmail_formal' => 0,
				'inmail_casual' => 0,
			]);
		}


		// 	inmail カウントアップ
        if ($interview->interview_type == '1') { // 正式応募
			$inmail->inmail_formal += 1;
		} else {
			$inmail->inmail_casual += 1;
		}
		$inmail->save();
		
		

		return redirect('comp/user/detail?user_id=' . $request->user_id);
	}


/*************************************
* ご請求予定
**************************************/
	public function billing(Request $request)
	{
		$loginUser = Auth::user();

		$billingList = Interview::where('interviews.company_id' ,$loginUser->company_id)
			->join('users', 'interviews.user_id','=','users.id')
			->join('jobs', 'interviews.job_id','=','jobs.id')
			->join('comp_members', 'interviews.member_id','=','comp_members.id')
			->selectRaw('interviews.* ,users.name as user_name, comp_members.name as member_name , jobs.name as job_name')
			->where('interviews.interview_type', '1')
			->where('interviews.result_id', '1')
			->whereNotNull('interviews.entrance_date')
			->get();

		return view('comp.billing_list' ,compact(
			'billingList',
			));
	}


/*************************************
* ご請求履歴
**************************************/
	public function billingHist(Request $request)
	{
		$loginUser = Auth::user();

		$billingList = Interview::where('interviews.company_id' ,$loginUser->company_id)
			->join('users', 'interviews.user_id','=','users.id')
			->join('jobs', 'interviews.job_id','=','jobs.id')
			->join('comp_members', 'interviews.member_id','=','comp_members.id')
			->selectRaw('interviews.* ,users.name as user_name, comp_members.name as member_name , jobs.name as job_name')
			->where('interviews.interview_type', '1')
			->where('interviews.result_id', '1')
			->whereNotNull('interviews.entrance_date')
			->paginate(10);

		return view('comp.billing_hist_list' ,compact(
			'billingList',
			));
	}

}
