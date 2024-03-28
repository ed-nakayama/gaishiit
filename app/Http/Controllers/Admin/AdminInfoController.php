<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log; 
use Illuminate\Validation\Rule;

use App\Http\Controllers\Controller;
use App\Models\Information;


class AdminInfoController extends Controller
{
	public function __construct()
	{
 		$this->middleware('auth:admin');
	}


/*************************************
* 初期表示
**************************************/
	public function index()
	{
		$infoList = Information::orderBy('updated_at' ,'desc')
			->paginate(10);

		return view('admin.info_list' ,compact(
			'infoList',
		));
	}


/*************************************
* 編集
**************************************/
	public function info(Request $request)
	{
		if ( isset($request->info_id) ) {
			$info = Information::select()
				->where('id' , $request->info_id)
				->first();

			if ( !isset($info) ) {
				abort(404);
			}
		} else {
			$info = new Information();
		}


		return view('admin.info' ,compact(
			'info',
		));
	}


/*************************************
* 保存
**************************************/
	public function store(Request $request)
	{

		$validatedData = $request->validate([
//    		'title'     => ['required', 'string', 'max:100'],
    		'content'     => ['required', 'string', 'max:400'],
//    		'open_type'    => ['required'],
    		]);

		if (isset($request->info_id)) {
			$info = Information::select()
				->where('id' , $request->info_id)
				->first();

			if (!isset($info)) {
				abort(404);
			}
			
			$info->content = $request->content;
//			$info->open_limit =$request->open_limit;
			$info->save();
			
		} else {
	        $faq = Information::create([
			'content'        => $request->content,
//			'open_limit'     => $request->open_limit,
        	]);
		}

		return redirect()->route('admin.info', [ 'info_id' => $info->id ] )->with('update_success', 'お知らせ情報を保存しました。');
	}


/*************************************
* 状態変更
**************************************/
	public function change( Request $request )
	{

		$info = Information::find($request->info_id);


		if ($request->del_flag == '1') {
			$info->delete();
			
		} else {
			if ($request->open_flag == '1') {
				$info->open_flag = 1;
			} else {
				$info->open_flag = 0;
			}
			$info->save();
		}
	
		if ($request->del_flag == '1') {
			return redirect('admin/info/list');
		} else {
			return redirect()->route('admin.info', [ 'info_id' => $info->id ] )->with('option_success', 'お知らせ情報を保存しました。');
		}
	}



}
