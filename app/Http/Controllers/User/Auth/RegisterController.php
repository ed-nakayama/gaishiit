<?php

namespace App\Http\Controllers\User\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;

use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log; 

use App\Models\User;
use App\Models\Company;
use App\Models\JobCatDetail;
use App\Models\SearchUserHist;

use App\Mail\RegisterToUser;
use App\Mail\RegisterToAdmin;


use Storage;
use Hashids\Hashids;

class RegisterController extends Controller
{
    use RegistersUsers;

    protected $redirectTo = RouteServiceProvider::USER_MYPAGE;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest:user');
    }

    // Guardの認証方法を指定
    protected function guard()
    {
        return Auth::guard('user');
    }

    private function make_nocomp_list($company ,$up)
    {
        $low = strtolower($up);

 		$arg = 0;
		$no_comp = array();
 
 		foreach ( $company as $comp ) {

			$init = substr($comp->name_english, 0, 1);

			if ( ($init == $up) || ($init == $low) ) {
				$no_comp[$arg]['id'] = $comp->id;
				$no_comp[$arg]['name'] = $comp->name;
				$arg++;
			}
			
		} // end foreach
      
		return $no_comp;
	}


    // 新規登録画面 1
    public function showRegistrationForm()
    {
        return view('user.auth.register');
    }


/*******************************************************
* 新規登録画面
********************************************************/
    public function getRegister()
    {
        return view('user.user_regist');
    }


/*******************************************************
* 新規ユーザ登録確認
********************************************************/
    public function confirm(Request $request)
    {
		if ($_SERVER["REQUEST_METHOD"] === "POST") {
			$validatedData = $request->validate([
				'user_name'        => ['required', 'string', 'max:60'],
				'email'            => ['required', 'string', 'email', 'max:60', 'unique:users'],
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
		}
	
		$reg = $request;

        return view('user.user_regist_confirm', compact('reg'));
	}


/*******************************************************
* 新規ユーザ登録
********************************************************/
    public function postRegister(Request $request)
    {
		$validatedData = $request->validate([
			'user_name'        => ['required', 'string', 'max:60'],
			'email'            => ['required', 'string', 'email', 'max:60', 'unique:users'],
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

		$location = null;
		if (!empty($request->request_location)) $location = implode(',', $request->request_location);

		$birthday = $request->selectYear . '-' . $request->selectMonth . '-' . $request->selectDate;

		$job_cats = null;
		if (!empty($request->job_cat_details)) {
			$job_cat_details = explode(',', $request->job_cat_details);

			$jobCatList = JobCatDetail::whereIn('id' ,$job_cat_details)
				->selectRaw('distinct job_cat_id')
				->get();

			$job_cat_list = null;
			foreach ($jobCatList as $cat) {
				$job_cat_list[] = $cat->job_cat_id;
			}
			$job_cats = implode(',', $job_cat_list);
		}

		// ユーザ登録
        $retUser = User::create([
            'name'             => $request->user_name,
            'email'            => $request->email,
            'birthday'         => $birthday,
            'sex'              => $request->sex,

            'graduation'       => $request->graduation,
            'company'          => $request->company,
            'job'              => $request->job,
            'mgr_year'         => $request->mgr_year,
            'mgr_member'       => $request->mgr_member,
            'occupation'       => $request->occupation,

            'section'          => $request->section,
            'job_title'        => $request->job_title,
            'job_content'      => $request->job_content,
            'actual_income'    => $request->actual_income,
            'ote_income'       => $request->ote_income,
            'old_company'      => $request->old_company,
            'request_carrier'  => $request->request_carrier,

            'change_time'      => $request->change_time,

            'request_location' => $location,
            'else_location'    => $request->else_location,

            'business_cats'    => $request->business_cats,
            'job_cats'         => $job_cats,
            'job_cat_details'  => $request->job_cat_details,
            'income'           => $request->income,
            'no_company'       => $request->no_company,
            
        ]);


        $user = User::find($retUser->id);
    
		$nickIds = new Hashids(config('app.user_nick_salt'), 10);
		$passIds = new Hashids(config('app.user_pass_salt'), 10);

		$user->nick_name = $nickIds->encode($user->id);
		$passwd = $passIds->encode($user->id);
        $user->pw_raw = $passwd;
        $user->password = Hash::make($passwd);

        $user->save();

		// 登録完了のお知らせ
		 Mail::send(new RegisterToUser($user));
		 Mail::send(new RegisterToAdmin());


		$searchUserHist = SearchUserHist::create([
			'user_id'  => $user->id,
			'locations'  => $location,
			'job_cat_details'  => $request->job_cat_details,
			'business_cats'  => $request->business_cats,

		]);

		return redirect('/register/complete');
		
	}



    // バリデーション
    protected function validator(array $request)
    {

    }

    // 登録処理
    protected function create(array $request)
    {

    }



}
