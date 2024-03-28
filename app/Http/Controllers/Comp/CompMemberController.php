<?php

namespace App\Http\Controllers\Comp;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\ConstLocation;
use App\Models\Company;
use App\Models\CompMember;
use App\Models\Interview;
use App\Models\InterviewMessage;
use App\Models\Event;

use App\Http\Requests\UpdatePasswordRequest;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log; 

use Illuminate\Validation\Rule;

use Storage;
use Validator;

use App\Mail\MemberRegister;
use Hashids\Hashids;


class CompMemberController extends Controller
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
		$loginUser = Auth::user();
		$comp_id = $loginUser->company_id;

		$memberList = CompMember::select()->where('company_id' , $loginUser->company_id)->get();

		return view('comp.member_list' ,compact('memberList'));
	}



/*************************************
* 一覧
**************************************/
	public function list(Request $request)
	{
        $member = CompMember::find($request->member_id);

		$validatedData = $request->validate([
			'email'     => [
				'required',
				'string',
				'email',
				'max:40',
//                 Rule::unique('comp_members')->ignore($request->member_id),
				Rule::unique('comp_members', 'email')->whereNull('deleted_at')->where('ark_priv' ,'0')->ignore($request->member_id),
                ],
			'name'     => [
				'required',
				'string',
				'max:100'
				],
		]);


		if ($request->del_flag == '1') {
			$member->delete();

		} else {
			$member->admin_flag = $request->admin_flag;
			$member->email = $request->email;
			$member->name = $request->name;
			
			$member->save();
		}

		return redirect('comp/member');
	}


	/*************************************
	* データ追加
	**************************************/
	public function add( Request $request ){

		$loginUser = Auth::user();

		$validatedData = $request->validate([
			'solo_email'    => [
				'required',
				'string',
				'email',
				'max:40',
//				'unique:comp_members,deleted_at,NULL'],
				Rule::unique('comp_members', 'email')->whereNull('deleted_at')->where('ark_priv' ,'0'),
				],
			'solo_name'     => [
				'required',
				'string',
				'max:100'],
		]);

		if (!empty($request->admin_flag)) {
			$admin_flag = '1';
		} else {
			$admin_flag = '0';
		}

		$member = new CompMember();

        $retMember = CompMember::create([
            'company_id' => $loginUser->company_id,
            'email'      => $request->solo_email,
            'name'       => $request->solo_name,
            'admin_flag'  => $admin_flag,
        ]);

        $member = CompMember::find($retMember->id);
        $comp = Company::find($member->company_id);

		$passIds = new Hashids(config('app.comp_pass_salt'), 10);
		$passwd = $passIds->encode($member->id);
        $member->pw_raw = $passwd;
        $member->password = Hash::make($passwd);

		$member->save();
	
		// 登録完了のお知らせ
		 Mail::send(new MemberRegister($comp ,$member));

		return redirect('comp/member');

    }


	/*************************************
	* 一括追加
	**************************************/
	public function more( Request $request ){


		$validatedData = $request->validate([
			'email.*'    => ['required_with:name.*', 'nullable' ,'email', 'max:40', 'unique:comp_members,email'],
			'name.*'     => ['required_with:email.*','nullable' ,'max:100'],
		]);

		$loginUser = Auth::user();

		$cnt = count($request->email);
		
		for ($i = 0; $i < $cnt; $i++) {

			if ($request->email[$i] != '') {
	        	$retMember = CompMember::create([
		            'company_id' => $loginUser->company_id,
	    	        'email'      => $request->email[$i],
	        	    'name'       => $request->name[$i],
		        ]);

		        $member = CompMember::find($retMember->id);
				$comp = Company::find($member->company_id);

				$passIds = new Hashids(config('app.comp_pass_salt'), 10);
				$passwd = $passIds->encode($member->id);
		        $member->pw_raw = $passwd;
		        $member->password = Hash::make($passwd);

				$member->save();

				// 登録完了のお知らせ
				 Mail::send(new MemberRegister($comp ,$member));
			}
		}

		return redirect('comp/member');

    }


	/*************************************
	* comp member再設定
	**************************************/
	public function reset_member( Request $request ) {
		
		foreach ($request->member as $member ) {
			CompMember::updateOrCreate(
				['id' => $member->id],
				['company_id' => $comp_id, 'name' => $member->name, 'email' => $member->email, 'admin_flag' => $member->admin_flag, 'comp_flag' => $member->comp_flag]
			);
		}

	}


	/*************************************
	* comp member再設定
	**************************************/
	public function member_activity() {

		$act = array('comp_name'=>''
			,'comp_logo'=>''
			,'user_formal_cnt'=>'0'
			,'user_casual_cnt'=>'0'
			,'event_cnt'=>'0'
			);
		
		$loginUser = Auth::user();
		$company = Company::find($loginUser->company_id);

		$act['mem_name'] = $loginUser->name;
		
		if (!empty($company)) {
			$act['comp_name'] = $company->name;
			$act['comp_logo'] = $company->logo_file;
		} else {
			$act['comp_name'] = "";
			$act['comp_logo'] = "";
		}

		// 企業カジュアル
		$comp_casual = interview::Join('companies', function ($join) use ($loginUser) {
    		$join->on('interviews.company_id', '=', 'companies.id')
				->where('companies.id' , $loginUser->company_id)
				->where('companies.person' , 'like' ,"%$loginUser->id%");
    		})
    		->Join('interview_msg_statuses', function ($join) use ($loginUser) {
    			$join->on('interview_msg_statuses.interview_id', '=', 'interviews.id')
				->where('interview_msg_statuses.reader_id' ,$loginUser->id)
				->where('interview_msg_statuses.read_flag', '0');
    		})
			->where('interviews.interview_type', '0')
			->where('interviews.interview_kind' , '0')
	    	->count();


		// 部署カジュアル
		$unit_casual = interview::Join('units', function ($join) use ($loginUser) {
    		$join->on('interviews.unit_id', '=', 'units.id')
				->where('units.company_id' , $loginUser->company_id)
				->where('units.person' , 'like' ,"%$loginUser->id%");
    		})
    		->Join('interview_msg_statuses', function ($join) use ($loginUser) {
    			$join->on('interview_msg_statuses.interview_id', '=', 'interviews.id')
				->where('interview_msg_statuses.reader_id' ,$loginUser->id)
				->where('interview_msg_statuses.read_flag', '0');
    		})
			->where('interviews.interview_type', '0')
			->where('interviews.interview_kind' , '1')
	    	->count();



		// ジョブカジュアル
		$job_casual = interview::Join('units', function ($join) use ($loginUser) {
    		$join->on('interviews.unit_id', '=', 'units.id')
				->where('units.company_id' , $loginUser->company_id)
				->where('units.person' , 'like' ,"%$loginUser->id%");
    		})
    		->Join('interview_msg_statuses', function ($join) use ($loginUser) {
    			$join->on('interview_msg_statuses.interview_id', '=', 'interviews.id')
				->where('interview_msg_statuses.reader_id' ,$loginUser->id)
				->where('interview_msg_statuses.read_flag', '0');
    		})
			->where('interviews.interview_type', '0')
			->where('interviews.interview_kind' , '2')
	    	->count();

		$act['user_casual_cnt'] = $comp_casual + $unit_casual + $job_casual;


		// ジョブ正式
		$act['user_formal_cnt'] = interview::Join('jobs', function ($join) use ($loginUser) {
    		$join->on('interviews.job_id', '=', 'jobs.id')
				->where('jobs.company_id' , $loginUser->company_id)
				->where('jobs.person' , 'like' ,"%$loginUser->id%");
    		})
    		->Join('interview_msg_statuses', function ($join) use ($loginUser) {
    			$join->on('interview_msg_statuses.interview_id', '=', 'interviews.id')
				->where('interview_msg_statuses.reader_id' ,$loginUser->id)
				->where('interview_msg_statuses.read_flag', '0');
    		})
			->where('interviews.interview_type' , '1')
	    	->count();


/*
		// 企業イベント
		$comp_event = Interview::Join('companies', function ($join) use ($loginUser) {
    		$join->on('interviews.company_id', '=', 'companies.id')
				->where('companies.id' , $loginUser->company_id)
				->where('companies.person' , 'like' ,"%$loginUser->id%");
    		})
    		->Join('interview_msg_statuses', function ($join) use ($loginUser) {
    			$join->on('interview_msg_statuses.interview_id', '=', 'interviews.id')
				->where('interview_msg_statuses.reader_id' ,$loginUser->id)
				->where('interview_msg_statuses.read_flag', '0');
    		})
			->where('interviews.interview_type' , '2')
	    	->count();


		// 部署イベント
		$unit_event = Interview::Join('units', function ($join) use ($loginUser) {
    		$join->on('interviews.unit_id', '=', 'units.id')
				->where('units.company_id' , $loginUser->company_id)
				->where('units.person' , 'like' ,"%$loginUser->id%");
    		})
    		->Join('interview_msg_statuses', function ($join) use ($loginUser) {
    			$join->on('interview_msg_statuses.interview_id', '=', 'interviews.id')
				->where('interview_msg_statuses.reader_id' ,$loginUser->id)
				->where('interview_msg_statuses.read_flag', '0');
    		})
			->where('interviews.interview_type' , '2')
	    	->count();

		$act['event_cnt'] = $comp_event + $unit_event;
*/

		// イベント
		$unit_event = Interview::Join('events', function ($join) use ($loginUser) {
    		$join->on('interviews.event_id', '=', 'events.id')
				->where('events.person' , 'like' ,"%$loginUser->id%");
    		})
    		->Join('interview_msg_statuses', function ($join) use ($loginUser) {
    			$join->on('interview_msg_statuses.interview_id', '=', 'interviews.id')
				->where('interview_msg_statuses.reader_id' ,$loginUser->id)
				->where('interview_msg_statuses.read_flag', '0');
    		})
			->where('interviews.interview_type' , '2')
	    	->count();

		$act['event_cnt'] = $unit_event;

		
		return $act;
	}


/*************************************
* 個人設定
**************************************/
	public function setting()
	{
		$loginUser = Auth::user();

		$member = CompMember::find($loginUser->id);

 		return view('comp.setting' ,compact(
 			'member',
 			));

	}


/*************************************
* 個人設定更新
**************************************/
	public function settingStore(Request $request)
	{
		$loginUser = Auth::user();

		$member = CompMember::find($loginUser->id);

		if (!empty($request->casual_mail_flag) ) {
			if ($member->casual_mail_flag == '0') $member->casual_mail_date = date("Y-m-d H:i:s");
			$member->casual_mail_flag = '1';
		} else {
			$member->casual_mail_flag = '0';
		}

		if (!empty($request->formal_mail_flag) ) {
			if ($member->formal_mail_flag == '0') $member->formal_mail_date = date("Y-m-d H:i:s");
			$member->formal_mail_flag = '1';
		} else {
			$member->formal_mail_flag = '0';
		}

		if (!empty($request->event_mail_flag) ) {
			if ($member->event_mail_flag == '0') $member->event_mail_date = date("Y-m-d H:i:s");
			$member->event_mail_flag = '1';
		} else {
			$member->event_mail_flag = '0';
		}

		$member->save();

        return redirect()->back()->with('update_success', 'メール受信設定を変更しました。');
	}


	/*************************************
	* パスワード変更入力
	**************************************/
	public function editPassword(){
        return view('comp.comp_password_edit');
    }


	/*************************************
	* パスワード変更更新
	**************************************/
    public function updatePassword(UpdatePasswordRequest $request){
        
        $user = \Auth::user();
        $user->pw_raw = $request->get('new-password');
        $user->password = bcrypt($request->get('new-password'));
        $user->save();

        return redirect()->back()->with('update_password_success', 'パスワードを変更しました。');
    }

}
