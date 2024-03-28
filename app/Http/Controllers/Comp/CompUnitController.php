<?php

namespace App\Http\Controllers\Comp;

use App\Http\Controllers\UnitController;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log; 
use Illuminate\Validation\Rule;

use App\Http\Controllers\Controller;
use App\Models\Unit;
use App\Models\UnitPr;
use App\Models\CompMember;


class CompUnitController extends Controller
{
	public $loginUser;
	
	public function __construct()
	{
 		$this->middleware('auth:comp');
	}


/*************************************
* 初期表示
**************************************/
	public function index()
	{
 		$param = array();
		$param['only_me'] = '1';

		$request = new Request();
	
		$unitList =  $this->search_list($request ,$param);

		return view('comp.unit_list' ,compact(
			'unitList',
			'param',
		));
	}


/*************************************
* 一覧
**************************************/
	public function list(Request $request)
	{
 		$param = array();
		$param['only_me'] = '';

		if ( $request->only_me == '1' ) $param['only_me'] = '1';

		$unitList =  $this->search_list($request ,$param);

		return view('comp.unit_list' ,compact(
			'unitList',
			'param',
		));
	}


/*************************************
* 一覧検索
**************************************/
	public function search_list(Request $request ,$param)
{
		$loginUser = Auth::user();

		$unitQuery = Unit::selectRaw("units.* ,(CASE WHEN units.person LIKE '%$loginUser->id%' THEN 1 ELSE 0 END) as edit_flag")
			->where('company_id' , $loginUser->company_id);
	
			
		if ($param['only_me'] == '1') {
			$unitQuery = $unitQuery->where('units.person' , 'like' ,"%$loginUser->id%");
		}

		$unitList = $unitQuery->orderBy('units.created_at' , 'desc')->paginate(10);

		$idx = 0;
		foreach ($unitList as $list) {
			
		if ( !empty($list->person) ) {
			$loc = explode(',', $list->person);
			$ln = CompMember::whereIn('id' ,$loc)->get();

				$person_name = array();
				for ($i = 0; $i < count($ln); $i++) {
					$person_name[] = $ln[$i]['name'];
				}

				$unitList[$idx++]->person_name = implode('/', $person_name);
			} else {
				$unitList[$idx++]->person_name = '';
			}
		}


		return $unitList;
}


/*************************************
* 新規作成
**************************************/
	public function getRegister()
	{
		$loginUser = Auth::user();

		$comp_id = $loginUser->company_id;
		$edit_flag = 1;

 		$memberList = CompMember::select('id', 'name')->where('company_id' , $comp_id)->get();

		$unit = new Unit();
		$unitPr = new UnitPr();

		return view('comp.unit_edit' ,compact(
			'unit',
			'unitPr',
			'memberList',
			'edit_flag',
		));
	}


/*************************************
* 新規登録
**************************************/
	public function postRegister(Request $request)
	{

		$loginUser = Auth::user();

		$comp_id = $loginUser->company_id;
		$unit_id = $request->unit_id;

		$validatedData = $request->validate([
    		'name'     => ['required', 'string', 'max:100'],
    		'intro'    => ['required', 'string'],
//    		'job_cat_id' => ['required'],
//    		'sub_category' => ['required', 'string', 'max:40'],
//   			'location' => ['required'],
   			'person'   => ['required'],
   		]);
		
		if ($request->casual_flag == '1') {
			$casual_flag = 1;
		} else {
			$casual_flag = 0;
		}
			

		$retUnit = Unit::updateOrCreate(
			['id' => $request->unit_id],
			['company_id' => $comp_id,
            'name'           => $request->name,
            'intro'          => $request->intro,
//            'job_cat_id'     => $request->job_cat_id,
//            'sub_category'   => $request->sub_category,
            'casual_flag'    => $casual_flag,
            'person'         => $request->person,
			]
		);

/*
		$cnt = count($request->unit_pr_id);
		for ($i = 0; $i < $cnt; $i++) {
			$pr_id = $request->unit_pr_id[$i];
			$headline = $request->headline[$i];
			$content = $request->content[$i];

			UnitPr::updateOrCreate(
				['id' => $pr_id],
				['unit_id' => $retUnit->id, 'company_id' => $comp_id, 'headline' => $headline, 'content' => $content]
			);
		}
*/
		return redirect()->route('comp.unit.edit', [ 'unit_id' => $unit_id ] )->with('update_success', '部門情報を保存しました。');
	}


/*************************************
* 編集
**************************************/
	public function edit( Request $request )
	{
		$loginUser = Auth::user();

		$comp_id = $loginUser->company_id;

 		$memberList = CompMember::select('id', 'name')->where('company_id' , $comp_id)->get();

		$unit = Unit::where('id' ,$request->unit_id)
			->where('company_id' ,$comp_id)
			->first();
		
		if (!isset($unit)) {
			abort(404);
		}
		
		$unitPr = UnitPr::select()->where('unit_id' , $unit->id)->get();

		$edit_flag = $request->edit_flag;
		
		return view('comp.unit_edit' ,compact(
			'unit',
			'unitPr',
			'memberList',
			'edit_flag',
		));
	}


/*************************************
* 状態変更
**************************************/
	public function change( Request $request )
	{
		$loginUser = Auth::user();
		$unit_id = $request->unit_id;

		$unit = Unit::find($request->unit_id);

		if ($request->del_flag == '1') {
			$unit->delete();
			
		} else {
			if ($request->open_flag == '1') {
				$unit->open_flag = 1;
			} else {
				$unit->open_flag = 0;
			}
			$unit->save();
		}
	
		if ($request->del_flag == '1') {
			return redirect('comp/unit');
		} else {
			return redirect()->route('comp.unit.edit', [ 'unit_id' => $unit_id ] )->with('option_success', '部門情報を保存しました。');
		}
		
//		return redirect('comp/unit');
	}



/*************************************
* 初期表示
**************************************/
	public function adminIndex()
	{
		$loginUser = Auth::user();

		$unitList = Unit::selectRaw("units.*")
			->where('company_id' , $loginUser->company_id)
			->orderBy('created_at' , 'desc')
			->paginate(10);

		return view('comp.admin_unit_list' ,compact(
			'unitList',
		));
	}



/*************************************
* 編集
**************************************/
	public function adminEdit( Request $request )
	{
		$loginUser = Auth::user();

		$comp_id = $loginUser->company_id;

 		$memberList = CompMember::select('id', 'name')->where('company_id' , $comp_id)->get();

		$unit = Unit::find($request->unit_id);

		return view('comp.admin_unit_edit' ,compact(
			'unit',
			'memberList',
		));
	}


/*************************************
* 新規作成
**************************************/
	public function adminGetRegister()
	{
		$loginUser = Auth::user();

		$comp_id = $loginUser->company_id;

 		$memberList = CompMember::select('id', 'name')->where('company_id' , $comp_id)->get();

		$unit = new Unit();

		return view('comp.admin_unit_edit' ,compact(
			'unit',
			'memberList',
		));
	}


/*************************************
* 新規登録
**************************************/
	public function adminPostRegister(Request $request)
	{

		$loginUser = Auth::user();

		$comp_id = $loginUser->company_id;

		$validatedData = $request->validate([
    		'name'     => ['required', 'string', 'max:100'],
   			'person'   => ['required'],
   		]);
		

		$retUnit = Unit::updateOrCreate(
			['id' => $request->unit_id],
			['company_id' => $comp_id,
            'name'           => $request->name,
            'person'         => $request->person,
			]
		);



		return redirect('comp/admin/unit');
	}


}
