<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Rpa;

class RpaController extends Controller
{
	public function __construct()
	{
 		$this->middleware('auth:admin');
	}

	public function index()
	{
		$rpa = Rpa::find(1);

		return view('admin.rpa' ,compact(
			'rpa',
		));
	}


	/**************************************
	 * 保存
	 **************************************/
	public function store(Request $request)
	{
		$rpa = Rpa::find(1);

		$rpa->keyword = $request->keyword;

		$rpa->save();
		
		return redirect('admin/unavailable')->with('update_success', '情報を保存しました。');
	}


}