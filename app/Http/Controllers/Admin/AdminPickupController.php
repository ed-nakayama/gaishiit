<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Company;
use App\Models\Pickup;

class AdminPickupController extends Controller
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
		$pickupList = Pickup::leftJoin('companies' ,'pickups.company_id', 'companies.id')
			->selectRaw('pickups.* , companies.name as company_name ')
			->orderBy('pickups.id')
			->get();

		$compList = Company::get();

		return view('admin.admin_pickup' ,compact(
			'pickupList',
			'compList',
			));
	}


/*************************************
* 登録
**************************************/
	public function store(Request $request)
	{
		$count = count($request->pickup_id);
		for ($i = 0; $i < $count; $i++) {
			$pickup = Pickup::find($request->pickup_id[$i]);
			$pickup->company_id = $request->company_id[$i];
			$pickup->memo = $request->memo[$i];
			$pickup->save();
		}

//		return redirect('admin/pickup');
		return redirect()->route('admin.pickup')->with('update_success', 'ピックアップ情報を変更しました。');
	}



}
