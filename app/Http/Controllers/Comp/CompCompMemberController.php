<?php

namespace App\Http\Controllers\Comp;

use App\Http\Controllers\CompMemberController;

use App\Http\Requests\UpdatePasswordRequest;

use App\Models\CompanyMenber;

use Hashids\Hashids;


class CompCompMemberController extends ClientController
{
	public $loginUser;

	public function __construct()
	{
 		$this->middleware('auth:comp');
	}


/*************************************
* パスワード更新
**************************************/
    public function updatePassword(UpdatePasswordRequest $request){
        
        $user = \Auth::user();
        $user->pw_raw = $request->get('password');
        $user->password = bcrypt($request->get('password'));
        $user->save();

        return redirect()->back()->with('update_password_success', 'パスワードを変更しました。');
    }


}
