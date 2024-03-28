<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    protected $redirectTo = RouteServiceProvider::HOME;

    public function __construct()
    {
        $this->middleware('guest:user')->except('logout');
    }

    // Guard��ǧ����ˡ�����
    protected function guard()
    {
        return Auth::guard('user');
    }

    // ���������
    public function showLoginForm()
    {
		if (!session()->has('url.intended')) {
            session(['url.intended' => url()->previous()]);
        }

        return view('user.auth.login');
    }

    // �������Ƚ���
    public function logout(Request $request)
    {
        Auth::guard('user')->logout();

        return $this->loggedOut($request);
    }

    // �������Ȥ������Υ�����쥯����
    public function loggedOut(Request $request)
    {
        return redirect(route('user.login'));
    }    
    
}
