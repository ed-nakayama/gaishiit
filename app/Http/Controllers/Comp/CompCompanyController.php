<?php

namespace App\Http\Controllers\Comp;

use App\Http\Controllers\CompanyController;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;


use App\Models\Company;
use App\Models\CompPr;
use App\Models\CompMember;
use App\Models\Job;
use App\Models\Event;
use App\Models\BusinessCatDetail;


class CompCompanyController extends CompanyController
{
	public function __construct()
	{
 		$this->middleware('auth:comp');
	}


/*************************************
* 編集
**************************************/
	public function edit()
	{

		$loginUser = Auth::user();

		$comp = Company::find($loginUser->company_id);
		$compPr = CompPr::select()->where('company_id' , $comp->id)->get();
 		$memberList = CompMember::select('id', 'name')->where('company_id' , $comp->id)->get();
    
		return view('comp.comp_edit' ,compact('comp' ,'compPr' ,'memberList'));
	}


/*************************************
* 保存
**************************************/
	public function store(Request $request)
	{

		$validatedData = $request->validate([
			'business_cat.*' => ['required'],
			'intro'        => ['required', 'string'],
//			'location'     => ['required'],
   			'person'       => ['required'],
//   		'logo'         => ['max:500|mimes:jpg,jpeg,png,gif'],
// 			'image'        => ['max:500|mimes:jpg,jpeg,png,gif'],
   		]);

		$loginUser = Auth::user();

		$comp = Company::find($loginUser->company_id);

		if (isset($request->casual_flag) ) {
			$comp->casual_flag = $request->casual_flag;
		} else {
			$comp->casual_flag = '0';
		}

		$comp->intro = $request->intro;
		$comp->person = $request->person;

		// ロゴファイル保存
		if (!empty($request->file('logo'))) {
			$file_name = $request->file('logo')->getClientOriginalName();
			$logo =  '/storage/comp/'  . $comp->id . '/'  . $file_name;
			$request->file('logo')->storeAs('public/comp/' . $comp->id ,$file_name);

			$comp->logo_file = $logo;
		}

		// イメージファイル保存
		if (!empty($request->file('image'))) {
			$file_name = $request->file('image')->getClientOriginalName();
			$image =  '/storage/comp/'  . $comp->id . '/'  . $file_name;
			$request->file('image')->storeAs('public/comp/' . $comp->id ,$file_name);

			$comp->image_file = $image;
		}
		

		// 業種保存
		if (!empty($request->businessCat)) {
			$temp = $request->businessCat;

			$business_cats = array();
			for ($i = 0 ; $i < count($temp); $i++) {
				$business_cats[] = '[' . $temp[$i] . ']';
			}
			$comp->business_cat_details = implode(',', $business_cats);

			// 業種カテゴリ
			$parList = BusinessCatDetail::whereIn('id' ,$temp)
				->selectRaw('distinct business_cat_id')
				->get();

			$catList  = array();
			foreach ($parList as $par) {
				$catList[] =  '[' . $par->business_cat_id . ']';
			}
			$comp->business_cats = implode(',', $catList);

		} else {
			$comp->business_cat_details = null;
			$comp->business_cats = null;
		}

		// こだわり保存
		if (!empty($request->commitCat)) {
			$temp = $request->commitCat;

			$commit_cats = array();
			for ($i = 0 ; $i < count($temp); $i++) {
				$commit_cats[] = '[' . $temp[$i] . ']';
			}
			$comp->commit_cats = implode(',', $commit_cats);

		} else {
			$comp->commit_cats = null;
		}


		$comp->save();

		return redirect()->route('comp.edit')->with('update_success', '企業情報を変更しました。');
	}


/*************************************
* 状態変更
**************************************/
	public function change( Request $request )
	{
		$loginUser = Auth::user();
		$comp_id = $request->comp_id;

		$comp = Company::find($request->comp_id);

		if ($request->open_flag == '1') {
			$comp->open_flag = 1;
		} else {
			$comp->open_flag = 0;
		}
		$comp->save();
	
		return redirect()->route('comp.edit', [ 'comp_id' => $comp_id ] )->with('option_success', '企業情報を保存しました。');
	}


/*************************************
* プレビュー
**************************************/
	public function preview(Request $request)
	{

		$loginUser = Auth::user();

		$comp = Company::where('companies.id' ,$loginUser->company_id)
			->selectRaw('companies.*')
			->first();

		$compPr = CompPr::select()->where('company_id' , $comp->id)->get();

		$eventList = Event::selectRaw('events.*')
			->where('event_kind' , '0')
			->where('events.company_id' ,$comp->id)
			->whereNull('events.unit_id')
			->where('events.open_flag' , '1')
			->orderBy('event_date' , 'desc')
			->limit(3)
			->get();

		$jobList = Job::selectRaw('jobs.*')
			->where('jobs.company_id' , $comp->id)
			->whereNull('jobs.unit_id')
			->where('jobs.open_flag' , '1')
			->limit(3)
			->get();


 		return view('comp.company_preview' ,compact(
 			'comp',
 			'compPr',
 			'eventList',
 			'jobList',
 			));
	}

}
