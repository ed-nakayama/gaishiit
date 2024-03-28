<?php

namespace App\Http\Controllers\Comp\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Models\CompMember;
use App\Models\Admin;

class VirtualLoginController extends Controller
{
    use AuthenticatesUsers;

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
    * 運営側 企業ログイン画面
	************************************************/
    public function showLoginForm2(Request $request)
    {
		$admin = Admin::find($request->id);

//		if (!isset($admin)) {
//			abort(404);
//		}

		if (strcmp($admin->comp_token, $request->_token) != 0 ) {
			abort(404);
		}
  		session()->put('ark_id', $request->id);
  
		$memberList = CompMember::where('ark_priv' ,'1')
			->join('companies' ,'comp_members.company_id' ,'companies.id')
			->selectRaw('comp_members.* ,companies.name as company_name')
			->get();
		
		return view('comp.virtual_login' ,compact(
			'memberList',
		));
    }

}
