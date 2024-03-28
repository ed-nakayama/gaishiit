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
     * Guard��ǧ����ˡ�����
	 ***********************************/
    protected function guard()
    {
        return Auth::guard('user');
    }


	/***********************************
     * �ȥåײ���
	 ***********************************/
    public function top()
    {
// T.N       return view('user.auth.login');
        return view('top');
    }


	/***********************************
     * ���������
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
	 * �������Ƚ���
	 ***********************************/
    public function logout(Request $request)
    {
		Auth::guard('user')->logout();

		return $this->loggedOut($request);
    }


	/***********************************
     * �������Ȥ������Υ�����쥯����
	 ***********************************/
    public function loggedOut(Request $request)
    {
//		return redirect(route('user.login'));
		return redirect('/');
    }


	/***********************************
     * �桼������õ��������ꤹ��
     *
     * @param  \Illuminate\Http\Request $request
     * @return Response
	 ***********************************/
    protected function credentials(Request $request)
    {
        return array_merge( 
            $request->only($this->username(), 'password'), // ɸ��ξ��
            [ 'aprove_flag' => 1 ] // �ɲþ��
        );
    }


}
