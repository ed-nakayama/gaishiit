<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

use App\Models\Admin;
use App\Models\Job;


class ApiController extends Controller
{
	public function __construct()
	{
// 		$this->middleware('auth:admin');
	}



/*************************************
* トークン保存
**************************************/
	public function updateToken(Request $request)
	{
		$admin = Admin::find($request->id);
		
		if (!isset($admin)) {
			abort(404);
		}

		$admin->comp_token = Str::random(32);
		$admin->save();

		return $admin->comp_token;
	}


/*************************************
* トークン保存
**************************************/
	public function joblist(Request $request)
	{

		$job = Job::find($request->id);

		if (!isset($job)) {
			abort(404);
		}

		$job->open_flag = '0';
//		$job->save();
		$job->delete();

		return "OK";
	}

}
