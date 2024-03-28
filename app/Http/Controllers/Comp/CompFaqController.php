<?php

namespace App\Http\Controllers\Comp;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

use App\Models\Company;
use App\Models\CompFaq;


class CompFaqController extends Controller
{
	public function __construct()
	{
 		$this->middleware('auth:comp');
	}


/*************************************
* 一覧
**************************************/
	public function index()
	{

		$loginUser = Auth::user();

		$faqList = CompFaq::select()->where('company_id' , $loginUser->company_id)->get();
    
		return view('comp.comp_faq_list' ,compact('faqList'));
	}


/*************************************
* 編集
**************************************/
	public function faq(Request $request)
	{

		$loginUser = Auth::user();

		if ( isset($request->faq_id) ) {
			$faq = CompFaq::select()
				->where('id' , $request->faq_id)
				->where('company_id' , $loginUser->company_id)
				->first();

			if ( !isset($faq) ) {
				abort(404);
			}
		} else {
			$faq = new CompFaq();
		}
		
		
		return view('comp.comp_faq' ,compact('faq'));
	}


/*************************************
* 保存
**************************************/
	public function store(Request $request)
	{

		$validatedData = $request->validate([
	  		'question' => ['required', 'string'],
	  		'answer'   => ['required', 'string'],
	  		'exp'      => ['required', 'string'],
   		]);

		$loginUser = Auth::user();

		if (isset($request->faq_id)) {
			$faq = CompFaq::select()
				->where('id' , $request->faq_id)
				->where('company_id' , $loginUser->company_id)
				->first();

			if (!isset($faq)) {
				abort(404);
			}
			
			$faq->question = $request->question;
			$faq->answer = $request->answer;
			$faq->exp = $request->exp;
			$faq->save();
			
		} else {
	        $faq = CompFaq::create([
    	        'company_id' => $loginUser->company_id,
    	        'question' => $request->question,
    	        'answer' => $request->answer,
    	        'exp' => $request->exp,
        	]);
		}


		return redirect()->route('comp.faq', [ 'faq_id' => $faq->id ] )->with('update_success', 'FAQ情報を保存しました。');
	}


/*************************************
* 状態変更
**************************************/
	public function change( Request $request )
	{
		$loginUser = Auth::user();
		$faq_id = $request->faq_id;

		$faq = CompFaq::find($request->faq_id);

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
			return redirect('comp/faq/list');
		} else {
			return redirect()->route('comp.faq', [ 'faq_id' => $faq_id ] )->with('option_success', 'FAQ情報を保存しました。');
		}
		
	}


}
