<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

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
    
    protected function validateEmail(Request $request)
	{
/*
    	$request->validate([
	        'email' => 'required|email|exists:users,email',
        }),
	    ], [
	        'email.exists' => 'このメールアドレスは登録されていません。'
	    ]);
*/

    	$request->validate([
	        'email' => [
	        	'required',
	        	'email',
	        	'exists:users,email',
	        	Rule::exists('users')->where('aprove_flag', 1),
	        ]
	    ]);

	}
}
