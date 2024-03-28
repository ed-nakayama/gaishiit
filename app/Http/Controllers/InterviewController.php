<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\JobCat;
use App\Models\Company;
use App\Models\CompMember;
use App\Models\ConstLocation;
use App\Models\Unit;
use App\Models\Job;
use App\Models\User;
use App\Models\Interview;
use App\Models\InterviewMessage;
use App\Models\InterviewMsgStatus;
use App\Models\EventHead;
use App\Models\EventMessage;
use App\Models\EventMsgStatus;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log; 
use Storage;
use Validator;

use App\Mail\NewMessageToComp;
use App\Mail\MessageToComp;
use App\Mail\AgentRequest;


class InterviewController extends Controller
{
	public function __construct()
	{
 		$this->middleware('auth:user');
	}


/*************************************
* 初期表示
**************************************/
	public function index()
	{
		$loginUser = Auth::user();

		$interviewList = Interview::join('users','interviews.user_id','=','users.id')
			->leftJoin('interview_msg_statuses', function ($join) use ($loginUser) {
                $join->on('interview_msg_statuses.interview_id','=','interviews.id')
					->where('interview_msg_statuses.reader_id' ,$loginUser->id);
			})
			->leftJoin('companies','interviews.company_id','=','companies.id')
			->leftJoin('units','interviews.unit_id','=','units.id')
			->leftJoin('jobs','interviews.job_id','=','jobs.id')
//			->whereNull('jobs.deleted_at')

			->addSelect(['last_update' => InterviewMessage::select('interview_messages.updated_at')
			    ->whereColumn('interview_messages.interview_id', 'interviews.id')
			    ->orderBy('interview_messages.updated_at', 'desc')
			    ->take(1)
			])
			
			->selectRaw('interviews.* ,interview_msg_statuses.read_flag, jobs.name as job_name ,units.name as unit_name , companies.name as company_name')
			->where('interviews.user_id' ,$loginUser->id)
//			->orderBy('interviews.updated_at' ,'desc')
			->orderBy('last_update' ,'desc')
			->paginate(10);

		$i = 0;
		foreach ($interviewList as $cas) {
	 		$msg = InterviewMessage::select('content')->where('interview_id', $cas->id)->orderBy('id', 'desc')->first();
 	 		$interviewList[$i++]->last_msg = $msg['content'];
		}
		
		
		return view('user.interview_list' ,compact(
			'interviewList',
			));
	}


/*************************************
* カジュアルリスト
**************************************/
	public function list(Request $request)
	{
		$loginUser = Auth::user();

		$interviewList =  Interview::join('users','interviews.user_id','=','users.id')
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
			
			->selectRaw('interviews.* ,interview_msg_statuses.read_flag, jobs.name as job_name ,units.name as unit_name , companies.name as company_name')
			->where('interviews.user_id' ,$loginUser->id);
			
		if (isset($request->interview_type) ) {
			$interviewList = $interviewList->where('interviews.interview_type' ,$request->interview_type);
			$intType = $request->interview_type;
		} else {
			$intType = '';
		}
	
//		$interviewList = $interviewList->orderBy('interviews.updated_at' ,'desc')
		$interviewList = $interviewList->orderBy('last_update' ,'desc')
			->paginate(10);

		$i = 0;
		foreach ($interviewList as $cas) {
	 		$msg = InterviewMessage::select('content')->where('interview_id', $cas->id)->orderBy('id', 'desc')->first();
 	 		$interviewList[$i++]->last_msg = $msg['content'];
		}
		
		$params['interview_type'] = $request->interview_type;

		return view('user.interview_list' ,compact(
			'params',
			'intType',
			'interviewList',
			));
	}


/*************************************
* インタビューフロー
**************************************/
	public function interviewFlow(Request $request)
	{
		$loginUser = Auth::user();

		$interview = Job::rightJoin('interviews','interviews.job_id','=','jobs.id')
			->leftJoin('companies','interviews.company_id','=','companies.id')
			->leftJoin('units','interviews.unit_id','=','units.id')
			->leftJoin('events','interviews.event_id','=','events.id')
			->selectRaw('interviews.*,' .
						'jobs.*, ' .
						'interviews.id as interview_id,' .
						'interviews.created_at as interviews_created_at,' .
						'jobs.name as job_name,' .
						'units.name as unit_name,' .
						'events.name as event_name,' .
						'companies.name as company_name, companies.logo_file as company_logo ')
			->where('interviews.id' ,$request->interview_id)
			->where('interviews.user_id' ,$loginUser->id)
			->first();

		if (!isset($interview)) {
			abort(404);
		}

		$msgList = InterviewMessage::leftJoin('users','interview_messages.user_id','=','users.id')
			->leftJoin('comp_members','interview_messages.member_id','=','comp_members.id')
			->selectRaw('interview_messages.*  ,users.name as user_name, comp_members.name as member_name')
			->where('interview_messages.interview_id' , $request->interview_id)
			->orderBy('interview_messages.id')
			->get();

		$userInfo = User::leftJoin('const_locations','users.request_location','=','const_locations.id')
			->selectRaw('users.* ,const_locations.name as location_name ')
			->where('users.id' ,$interview->user_id)
			->first();

		// 既読フラグセット
		InterviewMsgStatus::updateOrCreate(
			['interview_id' => $request->interview_id, 'reader_id' => $loginUser->id ],
			['reader_type' => 'U', 'read_flag' => '1']
		);


		$catName = array();
		$cats = explode(",", $userInfo['request_cat']);
		$len = count($cats);

		for ($i = 0; $i < $len; $i++) {
			$cat = JobCat::find($cats[$i]);
			if ($cat) {
				$catName[] = $cat->name;
			}
		}

		$userInfo['cat_names'] = join("/",$catName);


		
		return view('user.interview_flow' ,compact(
			'interview',
			'msgList',
			'userInfo',
			));
	}


/*************************************
* インタビューフローPOST
**************************************/
	public function interviewFlowPost(Request $request)
	{
		$loginUser = Auth::user();



		$interview = Interview::find($request->interview_id);

		$user = User::find($loginUser->id);
		$comp = Company::find($interview->company_id);

		if ($interview->aprove_flag == '0') {
			$validatedData = $request->validate([
				'content'     => ['required' ,'string'],
				'aprove_flag' => ['required'],
			]);

		} else {
			$validatedData = $request->validate([
				'content'     => ['required' ,'string'],
			]);
		}

		if ($interview->aprove_flag == '0') {
			$interview->aprove_flag = $request->aprove_flag;
			$interview->aprove_date = date("Y-m-d H:i:s");
			$interview->save();
		}


		InterviewMessage::create([
            'interview_id' => $request->interview_id,
            'user_id'      => $loginUser->id,
            'content'      => $request->content,
        ]);


		$person = array();
        
        // 未読フラグに設定
        if ($interview->interview_type == '1') { // 正式
			$job = Job::find($interview->job_id);
			$person = explode(",", $job['person']);

        } else if ($interview->interview_type == '2') { // イベント
	        if ($interview->interview_kind == '1') { // 部署
				$unit = Unit::find($interview->unit_id);
				$person = explode(",", $unit['person']);

	        } else { // 企業
//				$comp = Company::find($interview->company_id);
				$person = explode(",", $comp['person']);
			}

        } else { // カジュアル
	        if ($interview->interview_kind == '1') { // 部署
				$unit = Unit::find($interview->unit_id);
				$person = explode(",", $unit['person']);

	        } elseif ($interview->interview_kind == '2') { // ジョブ
				$job = Job::find($interview->job_id);
				$person = explode(",", $job['person']);

	        } else { // 企業
//				$comp = Company::find($interview->company_id);
				$person = explode(",", $comp['person']);
			}
        }

		$cnt = count($person);
		for ($i = 0; $i < $cnt; $i++) {
			InterviewMsgStatus::updateOrCreate(
				['interview_id' => $interview->id, 'reader_id' => $person[$i] ],
				['reader_type' => 'C', 'read_flag' => '0']
			);

			$member = CompMember::find($person[$i]);

			if (!empty($member)) {
				if ($interview->interview_type == '1' && $member->formal_mail_flag == '1') {
					Mail::send(new MessageToComp($user ,$comp ,$member ,$interview));
					$member->formal_mail_date = date("Y-m-d H:i:s");
					$member->save();

				} else if ($interview->interview_type == '2' && $member->event_mail_flag == '1') {
					Mail::send(new MessageToComp($user ,$comp ,$member ,$interview));
					$member->formal_mail_date = date("Y-m-d H:i:s");
					$member->save();
				
				} else if ($interview->interview_type == '0' && $member->casual_mail_flag == '1') {
					Mail::send(new MessageToComp($user ,$comp ,$member ,$interview));
					$member->formal_mail_date = date("Y-m-d H:i:s");
					$member->save();
				}
			}
		}

		return redirect('/interview/flow?interview_id=' . $request->interview_id);
	}


/*************************************
* インタビュー承認
**************************************/
	public function aprove(Request $request)
	{
		$loginUser = Auth::user();

		$interview = Interview::where('id' ,$request->interview_id)
			->where('user_id' , $loginUser->id)
			->where('aprove_flag' , '0')
			->first();

		if (!isset($interview)) {
			abort(404);
		}

		$interview->aprove_flag = $request->aprove_flag;
		$interview->aprove_date = date("Y-m-d H:i:s");
		$interview->save();

		$user = User::find($loginUser->id);
		$comp = Company::find($interview->company_id);


		$content ="";

		if ($interview->interview_type == '0') {
			if ($request->aprove_flag == '1') { // 承認のメール送信
				$content ="カジュアル面談の依頼が承認されました。";
				
			} elseif  ($request->aprove_flag == '2') {
				$content ="カジュアル面談への依頼が辞退されました。";
			}
			
		}

		if ($interview->interview_type == '1') {
			if ($request->aprove_flag == '1') { // 承認のメール送信
				$content ="正式応募への依頼が承認されました。";
				
			} elseif  ($request->aprove_flag == '2') {
				$content ="正式応募への依頼が辞退されました。";
			}
		}

		InterviewMessage::create([
            'interview_id' => $request->interview_id,
            'user_id'      => $loginUser->id,
            'content'      => $content,
        ]);


		$person = array();
        
        // 未読フラグに設定
        if ($interview->interview_type == '1') { // 正式
			$job = Job::find($interview->job_id);
			$person = explode(",", $job['person']);

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
        }

		$cnt = count($person);
		for ($i = 0; $i < $cnt; $i++) {
			InterviewMsgStatus::updateOrCreate(
				['interview_id' => $interview->id, 'reader_id' => $person[$i] ],
				['reader_type' => 'C', 'read_flag' => '0']
			);

			$member = CompMember::find($person[$i]);

			if (!empty($member)) {
				if ($interview->interview_type == '1' && $member->formal_mail_flag == '1') {
					Mail::send(new MessageToComp($user ,$comp ,$member ,$interview));
					$member->formal_mail_date = date("Y-m-d H:i:s");
					$member->save();

				} else if ($interview->interview_type == '0' && $member->casual_mail_flag == '1') {
					Mail::send(new MessageToComp($user ,$comp ,$member ,$interview));
					$member->casual_mail_date = date("Y-m-d H:i:s");
					$member->save();
				}
			}
		}


		return redirect('/interview/flow?interview_id=' . $request->interview_id);
	}



/*************************************
* インタビュー申し込み
**************************************/
	public function interviewRequest(Request $request)
	{

		$comp_id = $request->comp_id;
		$unit_id = null;
		$job_id = null;
		$event_id = null;
		$int_type = $request->int_type;
		$int_kind = null;

		if (isset($request->int_kind)) $int_kind = $request->int_kind;
		if (isset($request->unit_id)) $unit_id = $request->unit_id;
		if (isset($request->job_id)) $job_id = $request->job_id;
		if (isset($request->event_id)) $event_id = $request->event_id;

		$comp = Company::find($request->comp_id);

		$unit = "";
		if (isset($request->unit_id)) {
			$unit = Unit::leftJoin('job_cats' ,'units.job_cat_id' ,'job_cats.id')
				->selectRaw('units.* , job_cats.name as job_cat_name')
				->where('units.id' ,$request->unit_id)
				->first();
		}

		
		$job = "";
		if (isset($request->job_id)) {
			$job = Job::find($request->job_id);
		}

		return view('user.interview_request' ,compact(
			'int_type',
			'int_kind',
			'comp',
			'unit',
			'job',
			'event_id',
			));
	}


/*************************************
* インタビュー申し込み実行
**************************************/
	public function interviewRequestSend(Request $request)
	{
		$loginUser = Auth::user();

		$comp_id = $request->comp_id;
		$unit_id = null;
		$job_id = null;
		$event_id = null;
		$int_type = $request->int_type;
		$int_kind = null;

		if (isset($request->int_kind)) $int_kind = $request->int_kind;
		if (isset($request->unit_id)) $unit_id = $request->unit_id;
		if (isset($request->job_id)) $job_id = $request->job_id;
		if (isset($request->event_id)) $event_id = $request->event_id;

		if ($int_type == '0') {
			$content = "カジュアル面談を申し込みます。";
		} else if ($int_type == '1') {
			$content = "正式応募を申し込みます。";
		} else if ($int_type == '2') {
			$content = "イベントを申し込みます。";
		} else {
			$content = "";
		}

		$interview = Interview::create([
            'interview_type' => $int_type,
            'interview_kind' => $int_kind,
            'user_id'        => $loginUser->id,
            'company_id'     => $comp_id,
            'unit_id'        => $unit_id,
            'job_id'         => $job_id,
            'event_id'       => $event_id,
            'propose_type'   => '0',
            'last_update_id' => $loginUser->id,
        ]);

		InterviewMessage::create([
            'interview_id' => $interview->id,
            'user_id'      => $loginUser->id,
            'content'      => $content,
        ]);


		InterviewMsgStatus::Create([
			'interview_id' => $interview->id,
			'reader_type' => 'U',
			'reader_id' => $loginUser->id,
			'read_flag' => '1'
		]);


		$user = User::find($loginUser->id);
		$comp = Company::find($interview->company_id);

		$person = array();
        
        // 未読フラグに設定
        if ($interview->intervew_type == '1') { // 正式
			if (!empty($interview->job_id)) {
				$job = Job::find($interview->job_id);
				$person = explode(",", $job['person']);
			}
			
        } else if ($interview->intervew_type == '2') { // イベント
	        if ($interview->interview_kind == '1') { // 部署
				if (!empty($interview->unit_id)) {
					$unit = Unit::find($interview->unit_id);
					$person = explode(",", $unit['person']);
				}
	        } else { // 企業
				if (!empty($interview->company_id)) {
					$comp = Company::find($interview->company_id);
					$person = explode(",", $comp['person']);
				}
			}

        } else { // カジュアル
	        if ($interview->interview_kind == '1') { // 部署
				if (!empty($interview->unit_id)) {
					$unit = Unit::find($interview->unit_id);
					$person = explode(",", $unit['person']);
				}
			
	        } elseif ($interview->interview_kind == '2') { // ジョブ
				if (!empty($interview->job_id)) {
					$job = Job::find($interview->job_id);
					$person = explode(",", $job['person']);
				}
	        } else { // 企業
//				$comp = Company::find($interview->company_id);
				$person = explode(",", $comp['person']);
			}
        }


		$cnt = count($person);
		for ($i = 0; $i < $cnt; $i++) {
			InterviewMsgStatus::updateOrCreate(
				['interview_id' => $interview->id, 'reader_id' => $person[$i] ],
				['reader_type' => 'C', 'read_flag' => '0']
			);

			$member = CompMember::find($person[$i]);

			if (!empty($member)) {
				if ($interview->interview_type == '1' && $member->formal_mail_flag == '1') {
					Mail::send(new NewMessageToComp($user ,$comp ,$member ,$interview));
					$member->formal_mail_date = date("Y-m-d H:i:s");
					$member->save();

				} else if ($interview->interview_type == '2' && $member->event_mail_flag == '1') {
					Mail::send(new NewMessageToComp($user ,$comp ,$member ,$interview));
					$member->formal_mail_date = date("Y-m-d H:i:s");
					$member->save();
				
				} else if ($interview->interview_type == '0' && $member->casual_mail_flag == '1') {
					Mail::send(new NewMessageToComp($user ,$comp ,$member ,$interview));
					$member->formal_mail_date = date("Y-m-d H:i:s");
					$member->save();
				}
			}
		}


		return redirect()->route('user.interview.request', ['comp_id' => $comp_id ,'unit_id' => $unit_id ,'job_id' => $job_id ,'event_id' => $event_id ,'int_type' => $int_type ,'int_kind' => $int_kind ] )->with('send_success', '送信完了しました。');

	}


/*************************************
* エージェント相談申し込み
**************************************/
	public function agentRequest(Request $request)
	{
		$comp = Company::find($request->comp_id);

		$unit = "";
		if (!empty($request->unit_id)) {
			$unit = Unit::find($request->unit_id);
		}

		$job = "";
		if (isset($request->job_id)) {
			$job = Job::find($request->job_id);
		}

		return view('user.agent_request' ,compact(
			'comp',
			'unit',
			'job',
			));
	}


/*************************************
* エージェント相談申し込み実行
**************************************/
	public function agentRequestSend(Request $request)
	{
		$loginUser = Auth::user();

		$comp = Company::find($request->comp_id);

		$unit = "";
		$unit_id = "";
		if (!empty($request->unit_id)) {
			$unit = Unit::find($request->unit_id);
			$unit_id = $request->unit_id;
		}

		$job = "";
		$job_id = "";
		if (isset($request->job_id)) {
			$job = Job::find($request->job_id);
			$job_id = $request->job_id;
		}

		$content = "転職エージェントに相談を申し込みます。";

		Mail::send(new AgentRequest($loginUser ,$comp ,$unit ,$job));

		return redirect()->route('user.agent.request', ['comp_id' => $comp->id ,'unit_id' => $unit_id ,'job_id' => $job_id ] )->with('send_success', '送信完了しました。');

	}



}
