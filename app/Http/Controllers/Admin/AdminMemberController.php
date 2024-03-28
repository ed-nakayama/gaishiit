<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\ConstLocation;
use App\Models\Company;
use App\Models\CompMember;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log; 
use Illuminate\Support\Facades\Mail;

use Illuminate\Validation\Rule;

use Storage;
use Validator;

use Hashids\Hashids;

use App\Mail\MemberRegister;

class AdminMemberController extends Controller
{
	public $loginUser;
	
	public function __construct()
	{
 		$this->middleware('auth:admin');
	}


/*************************************
* 企業メンバー
**************************************/
	public function list( Request $request )
	{
		$comp = Company::find($request->company_id);
		$comp_id = $request->company_id;
		
		$memberList = CompMember::select()->where('company_id' , $request->company_id)->get();

		return view('admin.member_list' ,compact(
			'comp',
			'comp_id',
			'memberList',
		));
	}


/*************************************
* 一覧
**************************************/
	public function store(Request $request)
	{
        $member = CompMember::find($request->member_id);

		$validatedData = $request->validate([
			'email'     => [
				'required',
				'string',
				'email',
				'max:40',
//                 Rule::unique('comp_members')->ignore($request->member_id),
				Rule::unique('comp_members', 'email')->whereNull('deleted_at')->where('company_id' ,$member->company_id)->ignore($request->member_id),
                ],
			'name'     => ['required', 'string', 'max:100'],
		]);


		if ($request->del_flag == '1') {
			$member->delete();

		} else {
			if (!empty($request->admin_flag)) {
				$member->admin_flag = $request->admin_flag;
			} else {
				$member->admin_flag = 0;
			}

			if (!empty($request->ark_priv)) {
				$member->ark_priv = $request->ark_priv;
			} else {
				$member->ark_priv = 0;
			}
			
			$member->email = $request->email;
			$member->name = $request->name;
			
			$member->save();
		}



		$company_id = $request->company_id;

		return redirect()->route('admin.member.list', ['company_id' => $company_id ]);
	}


	/*************************************
	* データ追加
	**************************************/
	public function add( Request $request ){


		$validatedData = $request->validate([
			'solo_email'    => [
				'required',
				'string',
				'email',
				'max:40',
//				'unique:comp_members,email'],
				Rule::unique('comp_members', 'email')->whereNull('deleted_at')->where('company_id' ,$request->company_id),
				],
			'solo_name'     => [
				'required',
				'string',
				'max:100'
				],
		]);

		if (!empty($request->ark_priv)) {
			$ark_priv = '1';
		} else {
			$ark_priv = '0';
		}

		if (!empty($request->admin_flag)) {
			$admin_flag = '1';
		} else {
			$admin_flag = '0';
		}


		$member = new CompMember();

        $retMember = CompMember::create([
            'company_id'  => $request->company_id,
            'email'       => $request->solo_email,
            'name'        => $request->solo_name,
            'ark_priv'    => $ark_priv,
            'admin_flag'  => $admin_flag,
        ]);

        $member = CompMember::find($retMember->id);
        $comp = Company::find($member->company_id);

		$passIds = new Hashids(config('app.comp_pass_salt'), 10);
		$passwd = $passIds->encode($member->id);
        $member->pw_raw = $passwd;
        $member->password = Hash::make($passwd);

		$member->save();

		if ($ark_priv == '0') {
		// 登録完了のお知らせ
			Mail::send(new MemberRegister($comp ,$member));
		}
		
		$company_id = $request->company_id;

		return redirect()->route('admin.member.list', ['company_id' => $company_id ]);
    }


}
