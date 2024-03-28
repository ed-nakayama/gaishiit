<?php

namespace App\Http\Controllers\Comp\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

use App\Models\CompMember;
use App\Models\Admin;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    protected $redirectTo = RouteServiceProvider::COMP_MYPAGE;

    public function __construct()
    {
        $this->middleware('guest:comp')->except('logout');
    }

    // Guardの認証方法を指定
    protected function guard()
    {
        return Auth::guard('comp');
    }


	/***********************************************
    * ログイン画面
	************************************************/
    public function showLoginForm()
    {
        return view('comp.auth.login');
    }


	/***********************************************
	* email でも ID でもログイン可能に
	************************************************/
	protected function attemptLogin(Request $request)
	{
	    $username = $request->input($this->username());
	    $password = $request->input('password');
 
	    if (filter_var($username, \FILTER_VALIDATE_EMAIL)) {
	        $credentials = ['email' => $username, 'password' => $password];
	    } else {
	        $credentials = ['id' => $username, 'password' => $password];
	    }
 
	    return $this->guard()->attempt($credentials, $request->filled('remember'));
	} 


	/***********************************************
    * ログアウト処理
	************************************************/
    public function logout(Request $request)
    {
        $ses_all = session()->all();
        
        $member = '';
        foreach ($ses_all as $key => $value) {
			if(strpos($key,'login_comp') !== false) {
				$member = CompMember::find($value);
			}
        }
        
        Auth::guard('comp')->logout();

		if (!empty( $member)) {
			if ($member->ark_priv == '1') {
				
				$ark_id = session()->get('ark_id');

				if (empty($ark_id)) {
					abort(404);
				} else {
					$admin = Admin::find($ark_id);
				
					if (!isset($admin)) {
						abort(404);
					}
					$admin->comp_token = Str::random(32);
					$admin->save();
				}

				return redirect('/comp/virtual/login?id=' . $ark_id . "&_token=" . $admin->comp_token);
            }
        }

        return $this->loggedOut($request);
    }


	/***********************************************
    * ログアウトした時のリダイレクト先
	************************************************/
    public function loggedOut(Request $request)
    {
        return redirect(route('comp.login'));
    }    
}
