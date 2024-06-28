<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

use App\Models\AdminFaq;
use App\Models\AdminInquiry;


class AdminFaqController extends Controller
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
		$faqList = AdminFaq::select()->get();
    
		return view('admin.admin_faq_list' ,compact('faqList'));
	}


/*************************************
* 編集
**************************************/
	public function faq(Request $request)
	{
		if ( isset($request->faq_id) ) {
			$faq = AdminFaq::select()
				->where('id' , $request->faq_id)
				->first();

			if ( !isset($faq) ) {
				abort(404);
			}
		} else {
			$faq = new AdminFaq();
		}
		
		
		return view('admin.admin_faq' ,compact('faq'));
	}


/*************************************
* 保存
**************************************/
	public function store(Request $request)
	{

		$validatedData = $request->validate([
	  		'question' => ['required', 'string'],
//	  		'answer'   => ['required', 'string'],
//	  		'exp'      => ['required', 'string'],
   		]);

		if (isset($request->faq_id)) {
			$faq = AdminFaq::select()
				->where('id' , $request->faq_id)
				->first();

			if (!isset($faq)) {
				abort(404);
			}
			
			$faq->question = $request->question;
			$faq->answer = $request->answer;
			$faq->exp = $request->exp;
			$faq->save();
			
		} else {
	        $faq = AdminFaq::create([
    	        'question' => $request->question,
    	        'answer' => $request->answer,
    	        'exp' => $request->exp,
        	]);
		}

		return redirect()->route('admin.faq', [ 'faq_id' => $faq->id ] )->with('update_success', 'FAQ情報を保存しました。');
	}


/*************************************
* 状態変更
**************************************/
	public function change( Request $request )
	{
		$faq = AdminFaq::find($request->faq_id);

		if ($request->del_flag == '1') {
			$faq->delete();
			
		} else {
			if ($request->open_flag == '1') {
				$faq->open_flag = 1;
			} else {
				$faq->open_flag = 0;
			}
			$faq->save();
		}
	
		if ($request->del_flag == '1') {
			return redirect('admin/faq/list');
		} else {
			return redirect()->route('admin.faq', [ 'faq_id' => $faq->id ] )->with('option_success', 'FAQ情報を保存しました。');
		}
		
	}


/*************************************
* お問合せ一覧
**************************************/
	public function ask()
	{
		$askList = AdminInquiry::leftJoin('users', 'admin_inquiries.user_id','=','users.id')
			->selectRaw("admin_inquiries.* ,users.name as candidate_name")
			->orderBy('created_at' ,'desc')
			->paginate(10);

    
		return view('admin.admin_ask' ,compact('askList'));
	}




}
