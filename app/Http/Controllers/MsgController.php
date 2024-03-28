<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MsgController extends Controller
{
	public function __construct()
	{
 		$this->middleware('auth:user');
	}


/*************************************
* 初期表示
**************************************/
	public function index()
	{
	}


/*************************************
* 一覧
**************************************/
	public function list(Request $request)
	{
	}

}
