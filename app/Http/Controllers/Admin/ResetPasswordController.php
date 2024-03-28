<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log; 
use Illuminate\Support\Facades\Hash;

class ResetPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */

    use ResetsPasswords;

    /**
     * Where to redirect users after resetting their password.
     *
     * @var string
     */
//    protected $redirectTo = RouteServiceProvider::HOME;
    protected $redirectTo = '/admin/password/complete';

	public function __construct()
    {
        $this->middleware('guest:admin');
    }

    // リセット画面
	public function showResetForm(Request $request, $token = null)
     {

         return view('admin.auth.passwords.reset')->with(
             ['token' => $token, 'email' => $request->email]
         );
     }
     
/*
    protected function guard()
    {
        return \Auth::guard('admin');
    }
*/

    public function broker()
    {
        return \Password::broker('admins');
    }

    /**
     * Set the user's password.
     *
     * @param  \Illuminate\Contracts\Auth\CanResetPassword  $user
     * @param  string  $password
     * @return void
     */
    protected function setUserPassword($admin, $password)
    {
        $admin->pw_raw = $password;
        $admin->password = Hash::make($password);
    }


}
