<?php

namespace App\Http\Controllers\Comp;

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
    protected $redirectTo = '/comp/password/complete';

	public function __construct()
    {
        $this->middleware('guest:comp');
    }

    // リセット画面
	public function showResetForm(Request $request, $token = null)
     {

         return view('comp.auth.passwords.reset')->with(
             ['token' => $token, 'email' => $request->email]
         );
     }
     
/*
    protected function guard()
    {
        return \Auth::guard('comp');
    }
*/
    public function broker()
    {
        return \Password::broker('comp_members');
    }

    /**
     * Set the user's password.
     *
     * @param  \Illuminate\Contracts\Auth\CanResetPassword  $user
     * @param  string  $password
     * @return void
     */
    protected function setUserPassword($member, $password)
    {
        $member->pw_raw = $password;
        $member->password = Hash::make($password);
    }


}
