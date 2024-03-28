<?php

namespace App\Http\Controllers\Comp;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;

class ForgotPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
    */

    use SendsPasswordResetEmails;
    
    public function __construct()
    {
        $this->middleware('guest:comp');
    }

    public function showLinkRequestForm()
    {
        return view('comp.auth.passwords.email');
    }

    protected function guard()
    {
        return \Auth::guard('comp');
    }

    public function broker()
    {
        return \Password::broker('comp_members');
    }

}
