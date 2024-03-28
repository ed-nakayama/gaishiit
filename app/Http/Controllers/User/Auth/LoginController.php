<?php

namespace App\Http\Controllers\User\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    protected $redirectTo = RouteServiceProvider::USER_MYPAGE;

    public function __construct()
    {
        $this->middleware('guest:user')->except('logout');
    }

	/***********************************
     * Guardの認証方法を指定
	 ***********************************/
    protected function guard()
    {
        return Auth::guard('user');
    }


	/***********************************
     * トップ画面
	 ***********************************/
    public function top()
    {
// T.N       return view('user.auth.login');
        return view('top');
    }


	/***********************************
     * ログイン画面
	 ***********************************/
    public function showLoginForm()
    {
		if (array_key_exists('HTTP_REFERER', $_SERVER)) {
			$path = parse_url($_SERVER['HTTP_REFERER']);
			if (array_key_exists('host', $path)) {
				if ($path['host'] == $_SERVER['HTTP_HOST']) {
					session(['url.intended' => $_SERVER['HTTP_REFERER']]);
				}
			}
		}

		return view('user.auth.login');
// T.N	return view('top');
    }


	/***********************************
	 * ログアウト処理
	 ***********************************/
    public function logout(Request $request)
    {
		Auth::guard('user')->logout();

		return $this->loggedOut($request);
    }


	/***********************************
     * ログアウトした時のリダイレクト先
	 ***********************************/
    public function loggedOut(Request $request)
    {
//		return redirect(route('user.login'));
		return redirect('/');
    }


	/***********************************
     * ユーザーを探す条件を指定する
     *
     * @param  \Illuminate\Http\Request $request
     * @return Response
	 ***********************************/
    protected function credentials(Request $request)
    {
        return array_merge( 
            $request->only($this->username(), 'password'), // 標準の条件
            [ 'aprove_flag' => 1 ] // 追加条件
        );
    }


}
