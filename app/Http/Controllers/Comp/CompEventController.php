<?php

namespace App\Http\Controllers\Comp;

//use App\Http\Controllers\EventController;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log; 
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Mail;

use App\Models\User;
use App\Models\Company;
use App\Models\Unit;
use App\Models\CompMember;
use App\Models\Event;
use App\Models\EventPr;
use App\Models\Interview;
use App\Models\InterviewMessage;
use App\Models\InterviewMsgStatus;

use App\Mail\MessageToUser;


//class CompEventController extends EventController
class CompEventController extends Controller
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
 		$param = array();
		$param['only_me'] = '1';

		$request = new Request();

		$eventList =  $this->search_list($request ,$param);

		return view('comp.event_list' ,compact(
			'eventList',
			'param',
		));
	}


/*************************************
* 一覧
**************************************/
	public function list(Request $request)
	{
 		$param = array();
		$param['only_me'] = '';

		if ( $request->only_me == '1' ) $param['only_me'] = '1';
		
		$eventList =  $this->search_list($request ,$param);

		return view('comp.event_list' ,compact(
			'eventList',
			'param',
		));
	}


/*************************************
* 一覧検索
**************************************/
	public function search_list(Request $request ,$param)
{
		$loginUser = Auth::user();

		$eventQuery = Event::leftJoin('units', 'events.unit_id','=','units.id')
			->leftJoin('comp_members', 'events.member_id','=','comp_members.id')
			->selectRaw("events.* ,units.name as unit_name ,comp_members.name as member_name ,(CASE WHEN events.person LIKE '%$loginUser->id%' THEN 1 ELSE 0 END) as edit_flag")
			->where('events.company_id' , $loginUser->company_id);
	
			
		if ($param['only_me'] == '1') {
			$eventQuery = $eventQuery->where('events.person' , 'like' ,"%$loginUser->id%");
		}

		$eventList = $eventQuery->orderBy('events.created_at' , 'desc')->paginate(10);

		$idx = 0;
		foreach ($eventList as $list) {
			
		if ( !empty($list->person) ) {
			$loc = explode(',', $list->person);
			$ln = CompMember::whereIn('id' ,$loc)->get();

				$person_name = array();
				for ($i = 0; $i < count($ln); $i++) {
					$person_name[] = $ln[$i]['name'];
				}

				$eventList[$idx++]->person_name = implode('/', $person_name);
			} else {
				$eventList[$idx++]->person_name = '';
			}
		}

		return $eventList;
}


/*************************************
* 新規作成
**************************************/
	public function getRegister()
	{
		$loginUser = Auth::user();

		$comp_id = $loginUser->company_id;
		$edit_flag = 1;

 		$memberList = CompMember::select('id', 'name')->where('company_id' , $comp_id)->get();
 		$unitList = Unit::select('id', 'name')->where('company_id' , $comp_id)->get();

		$event = new Event();
		$eventPr = new EventPr();

		return view('comp.event_edit' ,compact(
			'event',
			'eventPr',
			'unitList',
			'memberList',
			'edit_flag',
		));
	}


/*************************************
* 新規登録
**************************************/
	public function postRegister(Request $request)
	{

		$loginUser = Auth::user();

		$comp_id = $loginUser->company_id;

       if ( $request->has('unit') ) {
			$validatedData = $request->validate([
//	    		'unit'     => ['required'],
	    		'name'     => ['required', 'string', 'max:100'],
	    		'intro'    => ['required', 'string'],
    			'person'   => ['required'],
	   			'image'    => ['mimes:jpg,jpeg,png,gif' ,'max:1024'],
    			'capacity'   => ['nullable', 'integer'],
    		]);
		} else {
			$validatedData = $request->validate([
	    		'name'     => ['required', 'string', 'max:100'],
	    		'intro'    => ['required', 'string'],
     			'person'   => ['required'],
   				'image'    => ['mimes:jpg,jpeg,png,gif' ,'max:1024'],
    			'capacity'   => ['nullable', 'integer'],
	   		]);
		}

		if (isset($request->unit) ) {
			$event_kind = 1;
		} else {
			$event_kind = 0;
		}
		
	
		// アップロードファイル保存
		if (!empty($request->file('image'))) {
			$file_name = $request->file('image')->getClientOriginalName();
			$image =  '/storage/comp/'  . $comp_id . '/event/'. $request->event_id . '/'  . $file_name;
			$request->file('image')->storeAs('public/comp/' . $comp_id .'/event/'. $request->event_id ,$file_name);

			$retEvent = Event::updateOrCreate(
				['id' => $request->event_id],
				['company_id' => $comp_id,
	            'unit_id'        => $request->unit,
	            'member_id'      => $loginUser->id,
				'event_kind'     => $event_kind,
	            'name'           => $request->name,
	            'intro'          => $request->intro,
	            'image'          => $image,
	            'event_date'     => $request->event_date,
	            'start_hour'     => $request->start_hour,
	            'start_min'      => $request->start_min,
	            'end_hour'       => $request->end_hour,
	            'end_min'        => $request->end_min,
	            'place'          => $request->place,
	            'access'         => $request->access,
	            'online_flag'    => $request->online_flag,
	            'deadline_date'  => $request->deadline_date,
	            'deadline_hour'  => $request->deadline_hour,
	            'deadline_min'   => $request->deadline_min,
	            'capacity'       => $request->capacity,
	            'person'         => $request->person,
				]
			);
		} else {

			$retEvent = Event::updateOrCreate(
				['id' => $request->event_id],
				['company_id'    => $comp_id,
	            'unit_id'        => $request->unit,
	            'member_id'      => $loginUser->id,
				'event_kind'     => $event_kind,
	            'name'           => $request->name,
	            'intro'          => $request->intro,
	            'event_date'     => $request->event_date,
	            'start_hour'     => $request->start_hour,
	            'start_min'      => $request->start_min,
	            'end_hour'       => $request->end_hour,
	            'end_min'        => $request->end_min,
	            'place'          => $request->place,
	            'access'         => $request->access,
	            'online_flag'    => $request->online_flag,
	            'deadline_date'  => $request->deadline_date,
	            'deadline_hour'  => $request->deadline_hour,
	            'deadline_min'   => $request->deadline_min,
	            'capacity'       => $request->capacity,
	            'person'         => $request->person,
				]
			);
		}



/*
		$cnt = count($request->event_pr_id);
		for ($i = 0; $i < $cnt; $i++) {
			$pr_id = $request->event_pr_id[$i];
			$headline = $request->headline[$i];
			$content = $request->content[$i];

			EventPr::updateOrCreate(
				['id' => $pr_id],
				['event_id' => $retEvent->id, 'company_id' => $comp_id, 'headline' => $headline, 'content' => $content]
			);
		}
*/

		return redirect()->route('comp.event.edit', [ 'event_id' => $retEvent->id ] )->with('update_success', 'イベント情報を変更しました。');
//		return redirect('comp/event');
	}


/*************************************
* 詳細
**************************************/
	public function detail( Request $request )
	{
		$loginUser = Auth::user();

		$comp_id = $loginUser->company_id;

		$event = Event::leftJoin('units', 'events.unit_id','=','units.id')
			->selectRaw('events.*, units.name as unit_name')
			->where('events.id' ,$request->event_id)
			->where('events.company_id' ,$comp_id)
			->first();

		if (!isset($event)) {
			abort(404);
		}

		$headList = Interview::join('users', 'interviews.user_id','=','users.id')
			->selectRaw('interviews.*, users.nick_name as nick_name, users.name as user_name')
 			->where('interviews.company_id' , $comp_id)
 			->where('interviews.interview_type' , '2')
			->where('event_id' , $event->id)
			->sortable()
			->get();

		$edit_flag = $request->edit_flag;
		
		return view('comp.event_detail' ,compact(
			'event',
			'headList',
			'edit_flag',
		));
	}



/*************************************
* 編集
**************************************/
	public function edit( Request $request )
	{
		$loginUser = Auth::user();

		$comp_id = $loginUser->company_id;

 		$memberList = CompMember::select('id', 'name')->where('company_id' , $comp_id)->get();
 		$unitList = Unit::select('id', 'name')->where('company_id' , $comp_id)->get();

		$event = Event::find($request->event_id);
		$eventPr = EventPr::select()->where('event_id' , $event->id)->get();

		$edit_flag = $request->edit_flag;
		
		return view('comp.event_edit' ,compact(
			'event',
			'eventPr',
			'unitList',
			'memberList',
			'edit_flag',
		));
	}


/*************************************
* 状態変更
**************************************/
	public function change( Request $request )
	{
		$loginUser = Auth::user();
		$event_id = $request->event_id;

		$event = Event::find($request->event_id);

		if ($request->del_flag == '1') {
			$event->delete();
			
		} else {
			if ($request->open_flag == '1') {
				$event->open_flag = 1;
			} else {
				$event->open_flag = 0;
			}
			$event->save();
		}
	
		if ($request->del_flag == '1') {
			return redirect('comp/event');
		} else {
			return redirect()->route('comp.event.detail', [ 'event_id' => $event_id ] )->with('option_success', 'イベント情報を保存しました。');
		}
	}



/*************************************
* 承認
**************************************/
	public function aprove( Request $request )
	{
		
		$validatedData = $request->validate([
    		'sel_aprove' => ['required'],
   		]);

		$loginUser = Auth::user();

		$comp = Company::find($loginUser->company_id);

		if (!empty($request->selUser)) {

			foreach ($request->selUser as $value) {

				$interview = Interview::find($value);
				$user = User::find($interview->user_id);

				if ( $interview->aprove_flag == '0' && ($request->sel_aprove == '1') || ($request->sel_aprove == '2') ) {  // 承認・否認
					$interview->aprove_flag = $request->sel_aprove;
					$interview->aprove_date = date("Y-m-d H:i:s");
					$interview->save();
				}
	
				// メッセージ作成
				InterviewMessage::create([
            		'interview_id' => $value,
            		'member_id'    => $loginUser->id,
            		'content'      => $request->content,
       			]);

				// メッセージステータス設定
				InterviewMsgStatus::updateOrCreate(
					['interview_id' => $interview->id, 'reader_id' => $interview->user_id ],
					['reader_type' => 'U', 'read_flag' => '0']
				);

				// イベントメール希望の場合
				if ($user->event_mail_flag == '1')  {
					Mail::send(new MessageToUser($user ,$comp ,$interview));
					$user->event_mail_date = date("Y-m-d H:i:s");
					$user->save();
				}
  			}
		}
		
		return redirect('comp/event/detail?event_id=' . $request->event_id);
	}


}
