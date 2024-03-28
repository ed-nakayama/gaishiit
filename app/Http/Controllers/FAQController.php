<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log; 
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Mail;

use App\Http\Controllers\Controller;

use App\Models\Company;
use App\Models\CompFaq;
use App\Models\AdminFaq;
use App\Models\CompInquiry;
use App\Models\AdminInquiry;

use App\Mail\FaqToUser;


class FAQController extends Controller
{
	public function __construct()
	{
//		$this->middleware('auth:user');
	}


/*************************************
* 初期表示
**************************************/
	public function index()
	{
	}


/*************************************
* QA詳細
**************************************/
	public function comp_faq (Request $request)
	{

		$qa_list = CompFaq::where('company_id' ,$request->company_id)
			->where('open_flag' ,'1')
			->get();

		$comp = Company::find($request->company_id);

 		return view('user.comp_faq' ,compact(
 			'comp',
 			'qa_list',
 			));
	}


/**************************************
 企業側 QA
**************************************/
	public function comp_faq_store(Request $request)
	{
		$loginUser = Auth::guard('user')->user();

		$validatedData = $request->validate([
			'content'   => ['required', 'string'],
		]);

		$retQa = CompInquiry::create([
			'user_id'    => $loginUser->id,
			'company_id' => $request->comp_id,
			'content'    => $request->content,
		]);

        return redirect()->back()->with('send_success', '送信しました。');

	}



/*************************************
* 運営側 QA
**************************************/
	public function admin_faq(Request $request)
	{

		$qa_list = AdminFaq::where('open_flag' ,'1')->get();

 		return view('user.admin_faq' ,compact(
 			'qa_list',
 			));
	}


/*************************************
* 運営側 QA
**************************************/
	public function admin_faq_store(Request $request)
	{
		$loginUser = Auth::guard('user')->user();

		if (!empty($loginUser->id)) {

			$validatedData = $request->validate([
				'content'   => ['required', 'string'],
			]);

			$retQa = AdminInquiry::create([
				'user_id'    => $loginUser->id,
				'content'    => $request->content,
			]);

		}else {
			$validatedData = $request->validate([
				'user_name' => ['required', 'string'],
				'email'     => ['required', 'string', 'email'],
				'content'   => ['required', 'string'],
			]);

			$retQa = AdminInquiry::create([
				'user_name' => $request->user_name,
				'email'     => $request->email,
				'content'   => $request->content,
			]);

			Mail::send(new FaqToUser($retQa->user_name, $retQa->email, $retQa->content));

		}

        return redirect()->back()->with('send_success', '送信しました。');

	}

}
