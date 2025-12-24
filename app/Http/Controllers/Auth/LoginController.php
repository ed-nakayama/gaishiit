<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Models\Blog;

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
		$blogList = Blog::where('open_flag' , '1')
			->orderBy('open_date', 'DESC')
			->orderBy('updated_at', 'DESC')
			->limit(3)
			->get();

        return view('top' ,compact(
			'blogList',
			));
    }


	/***********************************
     * ログイン画面
	 ***********************************/
    public function showLoginForm()
    {
		if (!session()->has('url.intended')) {
            session(['url.intended' => url()->previous()]);
        }

        return view('user.auth.login');
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
        return redirect(route('user.login'));
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
