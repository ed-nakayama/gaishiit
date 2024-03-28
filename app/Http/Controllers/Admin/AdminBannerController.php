<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Company;
use App\Models\Event;
use App\Models\Banner;

class AdminBannerController extends Controller
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
		$bannerList = Banner::leftJoin('companies' ,'banners.company_id', 'companies.id')
			->selectRaw('banners.* , companies.name as company_name ')
			->orderBy('banners.id')
			->get();

		$compList = Company::get();
		$eventList = Event::orderBy('updated_at', 'desc')->get();

		return view('admin.admin_banner' ,compact(
			'bannerList',
			'compList',
			'eventList',
			));
	}


/*************************************
* 登録
**************************************/
	public function store(Request $request)
	{
		$banner = Banner::find($request->banner_id);

		$banner->company_id = $request->company_id;
		$banner->event_id = $request->event_id;
		$banner->url = $request->url;
		$banner->memo = $request->memo;

			// イメージファイル保存
		if (!empty($request->file('image'))) {
			$file_name = $request->file('image')->getClientOriginalName();
			$image =  '/storage/banner/'  . $banner->id . '/'  . $file_name;
			$request->file('image')->storeAs('public/banner/' . $banner->id ,$file_name);

			$banner->image = $image;
		}

		$banner->save();

		return redirect()->route('admin.banner')->with('update_success' . $request->banner_id , 'バナー情報を変更しました。');
	}



}
