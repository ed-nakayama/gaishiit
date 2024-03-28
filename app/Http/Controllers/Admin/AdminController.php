<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log; 
use App\Http\Requests\UpdatePasswordRequest;

use Storage;
use Hashids\Hashids;
use Illuminate\Validation\Rule;

use App\Models\Admin;
use App\Mail\RegisterToAdminAccount;


class AdminController extends Controller
{
	public function __construct()
	{
 		$this->middleware('auth:admin');
	}



/*************************************
* 一覧
**************************************/
	public function index()
	{
 		$adminList = Admin::select()->get();

		return view('admin.admin_list' ,compact('adminList'));
	}


/*************************************
* 新規作成
**************************************/
	public function getRegister()
	{
		$admin = new Admin();

		return view('admin.admin_edit' ,compact(
			'admin',
		));
	}


/*************************************
* 新規登録
**************************************/
	public function postRegister(Request $request)
	{

		$validatedData = $request->validate([
			'name'   => ['required' ,'string'],
	    	'email'  => [
	    		'required',
	    		'string',
	    		'email',
	    		'max:40',
//	    		Rule::unique('admins')->ignore($request->admin_id)],
				Rule::unique('admins', 'email')->whereNull('deleted_at')->ignore($request->admin_id)],
		]);

		if (!empty($request->aprove_priv)) {
			$aprove_priv = 1;
		} else {
			$aprove_priv = 0;
		}

		if (!empty($request->comp_priv)) {
			$comp_priv = 1;
		} else {
			$comp_priv = 0;
		}

		if (!empty($request->bill_priv)) {
			$bill_priv = 1;
		} else {
			$bill_priv = 0;
		}

		if (!empty($request->info_priv)) {
			$info_priv = 1;
		} else {
			$info_priv = 0;
		}

		if (!empty($request->cat_priv)) {
			$cat_priv = 1;
		} else {
			$cat_priv = 0;
		}

		if (!empty($request->pickup_priv)) {
			$pickup_priv = 1;
		} else {
			$pickup_priv = 0;
		}

		if (!empty($request->account_priv)) {
			$account_priv = 1;
		} else {
			$account_priv = 0;
		}

		if (!empty($request->eval_priv)) {
			$eval_priv = 1;
		} else {
			$eval_priv = 0;
		}

		$retAdmin = Admin::updateOrCreate(
			['id' => $request->admin_id],
			['name'          => $request->name,
			'email'          => $request->email,
			'aprove_priv'    => $aprove_priv,
			'comp_priv'      => $comp_priv,
			'bill_priv'      => $bill_priv,
			'info_priv'      => $info_priv,
			'cat_priv'       => $cat_priv,
			'pickup_priv'    => $pickup_priv,
			'account_priv'   => $account_priv,
			'eval_priv'      => $eval_priv,
			]
		);

		$admin = Admin::find($retAdmin->id);
		
		if ($admin->password == '') {
			$passIds = new Hashids(config('app.admin_pass_salt'), 10);
	
			$passwd = $passIds->encode($admin->id);
        	$admin->pw_raw = $passwd;
    	    $admin->password = Hash::make($passwd);
	        $admin->save();

			// 登録完了のお知らせ
			 Mail::send(new RegisterToAdminAccount($admin));
		}
		
		
		return redirect('admin/admin/list');
	}


/*************************************
* 編集
**************************************/
	public function edit( Request $request )
	{
		$admin = Admin::find($request->admin_id);

		return view('admin.admin_edit' ,compact('admin',
		));
	}


/*************************************
* パスワード変更
**************************************/
	public function editPassword(){
        return view('admin.admin_password_edit');
    }


/*************************************
* パスワード変更
**************************************/
    public function updatePassword(UpdatePasswordRequest $request){
        $user = \Auth::user();
        $user->pw_raw = $request->get('new-password');
        $user->password = bcrypt($request->get('new-password'));
        $user->save();

        return redirect()->back()->with('update_password_success', 'パスワードを変更しました。');
    }
}
