<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log; 

use App\Models\CUserType;
use App\Models\Logging;

class LoggingController extends Controller
{
	public $loginUser;
	
	public function __construct()
	{
 		$this->middleware('auth:admin');
	}



/*************************************
* 一覧
**************************************/
	public function list(Request $request)
	{
		$query = Logging::query();
 

 		$param = array();
 		$param['from_date'] = '';
 		$param['to_date'] = '';
 		$param['mode_type'] = '';
 		$param['login_id'] = '';
 		$param['name'] = '';
 		$param['sub_id'] = '';
 		$param['sub_name'] = '';
 		$param['ip'] = '';
 		$param['user_agent'] = '';
 
         if (isset($request->from_date)) {
            $query->where('created_at', '>=', "{$request->from_date} 00:00:00");
            $param['from_date'] = $request->from_date;
		} else {
            $param['from_date'] = date("Y-m-d",strtotime("-1 month"));;
		}

         if (isset($request->to_date)) {
            $query->where('created_at', '<=', "{$request->to_date} 23:59:59");
            $param['to_date'] = $request->to_date;
		}

        if (isset($request->mode_type)) {
            $query->where('mode_type',"{$request->mode_type}");
            $param['mode_type'] = $request->mode_type;
		}

        if (isset($request->login_id)) {
            $query->where('login_id',"{$request->login_id}");
            $param['login_id'] = $request->login_id;
		}

        if (isset($request->name)) {
            $query->where('name', 'LIKE', "%{$request->name}%");
            $param['name'] = $request->name;
		}

        if (isset($request->sub_id)) {
            $query->where('sub_id',"{$request->sub_id}");
            $param['sub_id'] = $request->sub_id;
		}

        if (isset($request->sub_name)) {
            $query->where('sub_name', 'LIKE', "%{$request->sub_name}%");
            $param['sub_name'] = $request->sub_name;
		}

        if (isset($request->ip)) {
            $query->where('ip', "{$request->ip}");
            $param['ip'] = $request->ip;
		}

        if (isset($request->user_agent)) {
            $query->where('user_agent', 'LIKE', "%{$request->user_agent}%");
            $param['user_agent'] = $request->user_agent;
		}

 
//  		$cUserType = CUserType::select()->where('del_flag' , '0')->get();
//		$formCUserType = $cUserType->pluck('name','type_id')->prepend("" ,'');

		$logList = $query->orderBy('created_at' ,'desc')->paginate(10);

//		return view('admin.log_list' ,compact('logList' ,'param' ,'formCUserType'));
		return view('admin.log_list' ,compact('logList' ,'param'));
	}


}
