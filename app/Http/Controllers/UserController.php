<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log; 
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

use App\Http\Controllers\Controller;

use App\Http\Requests\UserRequest;
use App\Http\Requests\UpdatePasswordRequest;


use App\Models\User;
use App\Models\ConstCat;
use App\Models\Interview;
use App\Models\InterviewMessage;
use App\Models\InterviewMsgStatus;
use App\Models\Event;
use App\Models\BusinessCatDetail;
use App\Models\JobCatDetail;
use App\Models\ConstLocation;
use App\Models\Company;
use App\Models\Banner;

use Storage;
use Hashids\Hashids;

class UserController extends Controller
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
		$userList = User::select('id', 'name')->where('del_flag' , '0')->paginate(1);

		return view('user' ,compact('user'));
	}



/*************************************
* 一覧
**************************************/
	public function list(Request $request)
	{
		$query = User::query();

 		$paramList = array();
 		$paramList['name'] = '';
 		$paramList['name_kana'] = '';
 		$paramList['nick_name'] = '';
 		$paramList['zip_code'] = '';
 		$paramList['pref_code'] = '';
 		$paramList['address'] = '';
 		$paramList['tel'] = '';
 		$paramList['email'] = '';
 		$paramList['del_flag'] = '';
 
 		$cPref = CArea::select('pref_id', 'name')->where('domestic_flag' , '1')->get();
		$formCPref = $cPref->pluck('name','pref_id')->prepend("" ,'');

        if (isset($request->name)) {
            $query->where('users.name', 'LIKE', "%{$request->name}%");
            $paramList['name'] = $request->name;
		}

        if (isset($request->name_kana)) {
            $query->where('name_kana', 'LIKE', "%{$request->name_kana}%");
            $paramList['name_kana'] = $request->name_kana;
		}

        if (isset($request->nick_name)) {
            $query->where('nick_name', 'LIKE', "%{$request->nick_name}%");
            $paramList['nick_name'] = $request->nick_name;
		}

        if (isset($request->zip_code)) {
            $query->where('zip_code', 'LIKE', "%{$request->zip_code}%");
            $paramList['zip_code'] = $request->zip_code;
		}
		
        if (isset($request->pref_code)) {
            if ($request->pref_code != '') {
            	$query->where('pref_code', "{$request->pref_code}");
            	$paramList['pref_code'] = $request->pref_code;
        	}
		}

        if (isset($request->address)) {
            $query->where('address', 'LIKE', "%{$request->address}%");
            $paramList['address'] = $request->address;
		}

        if (isset($request->tel)) {
            $query->where('tel', 'LIKE', "%{$request->tel}%");
            $paramList['tel'] = $request->tel;
		}
        
        if (isset($request->email)) {
            $query->where('email', 'LIKE', "%{$request->email}%");
            $paramList['email'] = $request->email;
		}


		if (Auth::guard('comp')->check()) {
   	    	$query->where('del_flag', "0");
       		$paramList['del_flag'] = $request->del_flag;
   	    	$query->where('aprove_flag', "1");
       		$paramList['aprove_flag'] = $request->del_flag;

		} else {
	        if (isset($request->del_flag)) {
    	        if ($request->del_flag != '') {
        	    	$query->where('del_flag', "{$request->del_flag}");
            		$paramList['del_flag'] = $request->del_flag;
				}
        	}
		}
 
		$userList = $query->leftJoin('c_areas','users.pref_code','=','c_areas.pref_id')->select('users.*', 'c_areas.name as pref_name')->paginate(10);

		return view('user_list' ,compact('userList' ,'paramList' ,'formCPref'));
	}


/*************************************
* 個人設定
**************************************/
	public function setting()
	{
		$loginUser = Auth::user();

		$user = User::find($loginUser->id);

 		return view('user.setting' ,compact(
 			'user',
 			));

	}


/*************************************
* 個人設定更新
**************************************/
	public function settingStore(Request $request)
	{
		$loginUser = Auth::user();

		$user = User::find($loginUser->id);

		if (!empty($request->casual_mail_flag) ) {
			if ($user->casual_mail_flag == '0') $user->casual_mail_date = date("Y-m-d H:i:s");
			$user->casual_mail_flag = '1';
		} else {
			$user->casual_mail_flag = '0';
		}

		if (!empty($request->formal_mail_flag) ) {
			if ($user->formal_mail_flag == '0') $user->formal_mail_date = date("Y-m-d H:i:s");
			$user->formal_mail_flag = '1';
		} else {
			$user->formal_mail_flag = '0';
		}

		if (!empty($request->event_mail_flag) ) {
			if ($user->event_mail_flag == '0') $user->event_mail_date = date("Y-m-d H:i:s");
			$user->event_mail_flag = '1';
		} else {
			$user->event_mail_flag = '0';
		}

		if (!empty($request->job_mail_flag) ) {
			if ($user->job_mail_flag == '0') $user->job_mail_date = date("Y-m-d H:i:s");
			$user->job_mail_flag = '1';
		} else {
			$user->job_mail_flag = '0';
		}

		if (!empty($request->favorite_mail_flag) ) {
			if ($user->favorite_mail_flag == '0') $user->favorite_mail_date = date("Y-m-d H:i:s");
			$user->favorite_mail_flag = '1';
		} else {
			$user->favorite_mail_flag = '0';
		}

		$user->save();

        return redirect()->back()->with('update_success', 'メール受信設定を変更しました。');
	}


	/*************************************
	* 編集
	**************************************/
	public function edit( Request $request )
	{
	}


	/*************************************
	* 基本情報
	**************************************/
	public function base( Request $request )
	{
		$loginUser = Auth::user();

		$user = User::find($loginUser->id);

 		return view('user.base' ,compact(
 			'user',
 			));
	
	}


	/*************************************
	* オプション保存
	**************************************/
	public function option( Request $request )
	{
		$validatedData = $request->validate([
    		'business_cats'   => ['required'],
    		'job_cat_details' => ['required'],
   		]);

		$loginUser = Auth::user();
		$user = User::find($loginUser->id);

		$job_cat_details = explode(',', $request->job_cat_details);

		$jobCatList = JobCatDetail::whereIn('id' ,$job_cat_details)
			->selectRaw('distinct job_cat_id')
			->get();

		$job_cat_list = null;
		foreach ($jobCatList as $cat) {
			$job_cat_list[] = $cat->job_cat_id;
		}
		$job_cats = implode(',', $job_cat_list);

		$user->business_cats = $request->business_cats;
		$user->job_cats = $job_cats;
		$user->job_cat_details = $request->job_cat_details;
		$user->no_company = $request->no_company;
		$user->save();

        return redirect()->back()
			->with('status', 'success-update2');

	}


	/*************************************
	* 基本情報　保存
	**************************************/
	public function base_store( Request $request )
	{
		$validatedData = $request->validate([
			'user_name'        => ['required', 'string', 'max:60'],
			'selectYear'       => ['required'],
			'selectMonth'      => ['required'],
			'selectDate'       => ['required'],
			'sex'              => ['required'],

			'graduation'       => ['required', 'string', 'max:100'],
			'company'          => ['required', 'string', 'max:80'],
			'job'              => ['required'],
			'mgr_year'         => ['required_if:job,2', 'integer' ,'nullable'],
			'mgr_member'       => ['required_if:job,2', 'integer' ,'nullable'],
			'occupation'       => ['required', 'string'],

			'section'          => ['nullable', 'string', 'max:200'],
			'job_title'        => ['nullable','string', 'max:40'],
			'job_content'      => ['nullable','string'],
			'actual_income'    => ['nullable','integer'],
    		'ote_income'       => ['nullable','integer'],
			'old_company'      => ['nullable','string', 'max:80'],
			'request_carrier'  => ['nullable','string'],

			'change_time'      => ['required'],

			'request_location' => ['required'],
			'business_cats'    => ['required'],
			'job_cat_details'  => ['required'],
			'income'           => ['required'],
   		]);

		$birthday = $request->selectYear . '-' . $request->selectMonth . '-' . $request->selectDate;

		$loginUser = Auth::user();

		$user = User::find($loginUser->id);

		$user->name = $request->user_name;
		$user->birthday = $birthday;
		$user->sex = $request->sex;

		$user->graduation = $request->graduation;
		$user->company = $request->company;
		$user->job = $request->job;
		$user->mgr_year = $request->mgr_year;
		$user->mgr_member = $request->mgr_member;
		$user->occupation = $request->occupation;

		$user->section = $request->section;
		$user->job_title = $request->job_title;
		$user->job_content = $request->job_content;
		$user->actual_income = $request->actual_income;
		$user->ote_income = $request->ote_income;
		$user->actual_income = $request->actual_income;
		$user->ote_income = $request->ote_income;
		$user->request_carrier = $request->request_carrier;

		$user->change_time = $request->change_time;

		$user->request_location = implode(',', $request->request_location);
		$user->else_location =  $request->else_location;

		$user->business_cats = $request->business_cats;
		$user->job_cat_details = $request->job_cat_details;
		$user->income = $request->income;
		$user->no_company = $request->no_company;

		$user->save();

        return redirect()->back()
			->with('status', 'success-update');

	}


	/*************************************
	* 職務経歴書
	**************************************/
	public function cv( Request $request )
	{
		$loginUser = Auth::user();

		$user = User::find($loginUser->id);

 		return view('user.cv' ,compact(
 			'user',
 			));
	}


	/*************************************
	* 職務経歴書　保存1
	**************************************/
	public function cv_store1( Request $request )
	{
		$loginUser = Auth::user();

		$user = User::find($loginUser->id);

		$user->unit_name = $request->unit_name;
		$user->enroll_from_year = $request->enroll_from_year;
		$user->enroll_from_month = $request->enroll_from_month;
		$user->enroll_from_day = $request->enroll_from_day;
		$user->enroll_to_year = $request->enroll_to_year;
		$user->enroll_to_month = $request->enroll_to_month;
		$user->enroll_to_day = $request->enroll_to_day;

		$user->job_detail = $request->job_detail;

		$user->save();

        return redirect()->back()
			->with('status', 'success-update');

	}


	/*************************************
	* 職務経歴書　保存2
	**************************************/
	public function cv_store2( Request $request )
	{
		$loginUser = Auth::user();

		$user = User::find($loginUser->id);

		$user->job_detail = $request->job_detail;

		$user->save();

        return redirect()->back()
			->with('status', 'success-update2');

	}


	/*************************************
	* 職務経歴書　保存3
	**************************************/
	public function cv_store3( Request $request )
	{
		$loginUser = Auth::user();

		$user = User::find($loginUser->id);

		$user->award = $request->award;
		if (isset($request->english) ) $user->english = $request->english;
		$user->toeic = $request->toeic;
		if (isset($request->japanese) ) $user->japanese = $request->japanese;

		$user->save();

        return redirect()->back()
			->with('status', 'success-update2');
	}


	/*************************************
	* 職務経歴書（英語）
	**************************************/
	public function cv_eng( Request $request )
	{
		$loginUser = Auth::user();

		$user = User::find($loginUser->id);

 		return view('user.cv_eng' ,compact(
 			'user',
 			));
	
	}


	/*************************************
	* 職務経歴書（英語）　保存1
	**************************************/
	public function cv_eng_store1( Request $request )
	{
		$loginUser = Auth::guard('user')->user();

		$user = User::find($loginUser->id);

		$user->en_company = $request->en_company;
		$user->en_unit_name = $request->en_unit_name;
		$user->en_job_title = $request->en_job_title;
		$user->enroll_from_year = $request->enroll_from_year;
		$user->enroll_from_month = $request->enroll_from_month;
		$user->enroll_from_day = $request->enroll_from_day;
		$user->enroll_to_year = $request->enroll_to_year;
		$user->enroll_to_month = $request->enroll_to_month;
		$user->enroll_to_day = $request->enroll_to_day;

		$user->en_job_detail = $request->en_job_detail;

		$user->save();

        return redirect()->back()
			->with('status', 'success-update');
	}


	/*************************************
	* 職務経歴書（英語）　保存2
	**************************************/
	public function cv_eng_store2( Request $request )
	{
		$loginUser = Auth::guard('user')->user();

		$user = User::find($loginUser->id);

		$user->en_job_detail = $request->en_job_detail;

		$user->save();

        return redirect()->back();
	}


	/*************************************
	* 職務経歴書（英語）　保存3
	**************************************/
	public function cv_eng_store3( Request $request )
	{
		$loginUser = Auth::guard('user')->user();

		$user = User::find($loginUser->id);

		$user->en_award = $request->en_award;
		if (isset($request->english) ) $user->english = $request->english;
		$user->toeic = $request->toeic;
		if (isset($request->japanese) ) $user->japanese = $request->japanese;

		$user->save();

        return redirect()->back()
			->with('status', 'success-update2');
	}



	/*************************************
	* 履歴書
	**************************************/
	public function vitae( Request $request )
	{
		$loginUser = Auth::guard('user')->user();

		$user = User::find($loginUser->id);

 		return view('user.vitae' ,compact(
 			'user',
 			));
	
	}


	/*************************************
	* 履歴書　保存
	**************************************/
	public function vitae_store( Request $request )
	{
		$loginUser = Auth::guard('user')->user();

		$user = User::find($loginUser->id);

		$user->name_kana = $request->name_kana;
		$user->pref = $request->pref;
		$user->address = $request->address;
//		$user->hist_email = $request->hist_email;
		$user->job_hist = $request->job_hist;
		$user->motivation = $request->motivation;
		$user->dependents = $request->dependents;
		if (isset($request->spouse) ) $user->spouse = $request->spouse;
		if (isset($request->obligation) ) $user->obligation = $request->obligation;

		$user->save();

        return redirect()->back()
			->with('status', 'success-update');
	}


	/*************************************
	* activity 再設定
	**************************************/
	public function user_activity() {

		$loginUser = Auth::guard('user')->user();

		$user = User::find($loginUser->id);

		$rate = 20;
		$cv_comp = 4;
		$cv_eng_comp = 0;
		$vitae_comp = 0;

		// 職務経歴書完成度チェック
		if ( !empty($user->unit_name)
			&& !empty($user->enroll_from_year) && !empty($user->enroll_from_month) && !empty($user->enroll_from_day)
			&& (
				 ( !empty($user->enroll_to_year) && !empty($user->enroll_to_month) && !empty($user->enroll_to_day) )
			  || ( empty($user->enroll_to_year) && empty($user->enroll_to_month) && empty($user->enroll_to_day) )
				)
		) {
			$rate += 25;
			$cv_comp--;
		}
		if (!empty($user->job_detail)) {
			$rate += 25;
			$cv_comp--;
		}
		
		if ( !empty($user->english) && !empty($user->japanese) ) {
			$rate += 25;
			$cv_comp--;
		}

		// 職務経歴書（英語）完成度チェック
		if ( !empty($user->en_company) && !empty($user->en_unit_name)
			&& !empty($user->en_job_title) && !empty($user->en_job_detail)
			&& !empty($user->enroll_from_year) && !empty($user->enroll_from_month) && !empty($user->enroll_from_day)
			&& (
				 ( !empty($user->enroll_to_year) && !empty($user->enroll_to_month) && !empty($user->enroll_to_day) )
			  || ( empty($user->enroll_to_year) && empty($user->enroll_to_month) && empty($user->enroll_to_day) )
				)

			&& !empty($user->english) && !empty($user->japanese) ) {
			$cv_eng_comp = 1;
		}
		
		// 履歴書完成度チェック
		if ( !empty($user->name_kana) && !empty($user->pref) && !empty($user->address)
			&& !empty($user->job_hist) && !empty($user->motivation) && !is_null($user->dependents)
			&& !empty($user->spouse) && !empty($user->obligation) ) {
			$rate += 5;
			$vitae_comp = 1;
		}
		
		$user_act = array(
			'casual_cnt'  => '0',
			'formal_cnt'  => '0',
			'event_cnt'   => '0',
			'unread_cnt'   => '0',
			'cv_comp'     => $cv_comp,
			'cv_eng_comp' => $cv_eng_comp,
			'vitae_comp'  => $vitae_comp,
			'rate'        => $rate,
			);

		// カジュアル未読
		$user_act['casual_cnt'] = interview::Join('interview_msg_statuses', function ($join) use ($loginUser) {
    		$join->on('interview_msg_statuses.interview_id', '=', 'interviews.id')
				->where('interview_msg_statuses.reader_type' ,'U')
				->where('interview_msg_statuses.reader_id' ,$loginUser->id)
				->where('interview_msg_statuses.read_flag' ,'0');
    		})
    		->select('interviews.id')
    		->where('interview_type' ,'0')
    		->count();

		// 正式未読
		$user_act['formal_cnt'] = interview::Join('interview_msg_statuses', function ($join) use ($loginUser) {
    		$join->on('interview_msg_statuses.interview_id', '=', 'interviews.id')
				->where('interview_msg_statuses.reader_type' ,'U')
				->where('interview_msg_statuses.reader_id' ,$loginUser->id)
				->where('interview_msg_statuses.read_flag' ,'0');
    		})
    		->select('interviews.id')
    		->where('interview_type' ,'1')
    		->count();

		// イベント未読
		$user_act['event_cnt'] = interview::Join('interview_msg_statuses', function ($join) use ($loginUser) {
    		$join->on('interview_msg_statuses.interview_id', '=', 'interviews.id')
				->where('interview_msg_statuses.reader_type' ,'U')
				->where('interview_msg_statuses.reader_id' ,$loginUser->id)
				->where('interview_msg_statuses.read_flag' ,'0');
    		})
    		->where('interview_type' ,'2')
    		->count();

		$user_act['unread_cnt'] = $user_act['casual_cnt'] + $user_act['formal_cnt'] + $user_act['event_cnt'];


		return $user_act;
	}


/*************************************
* パスワード変更
**************************************/
	public function editPassword(){
        return view('user.user_password_edit');
    }


/*************************************
* パスワード更新
**************************************/
    public function updatePassword(UpdatePasswordRequest $request){

//        $user = \Auth::user();
        $user = Auth::guard('user')->user();

        $user->pw_raw = $request->get('new-password');
        $user->password = bcrypt($request->get('new-password'));
        $user->save();

        return redirect()->back()->with('update_password_success', 'パスワードを変更しました。');
    }


}
