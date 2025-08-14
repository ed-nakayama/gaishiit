<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Models\LpRef;

class LpController extends Controller
{

/*************************************
* LP TOP
**************************************/
	public function index(Request $request)
	{
//		dd($request);
		$referer = $request->headers->get('referer');
		$ip = $request->headers->get('x-real-ip');


		$lpRef = LpRef::create([
			'ip' => $ip,
			'referer' => $referer,
		]);

		return view('user.ad-lp1');
	}


}
