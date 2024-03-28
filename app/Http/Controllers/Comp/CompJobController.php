<?php

namespace App\Http\Controllers\Comp;

use App\Http\Controllers\JobController;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log; 
use Illuminate\Validation\Rule;

use App\Http\Controllers\Controller;
use App\Models\Unit;
use App\Models\Company;
use App\Models\CompMember;
use App\Models\Job;
use App\Models\JobPr;
use App\Models\JobCatDetail;
use App\Models\IndustoryCatDetail;


class CompJobController extends Controller
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
		$only_me = '1';
		$freeword = '';

		$request = new Request();

		$jobList =  $this->search_list($only_me ,$freeword);
		
		return view('comp.job_list' ,compact(
			'jobList',
			'only_me',
			'freeword',
		));
	}


/*************************************
* 一覧
**************************************/
	public function list(Request $request)
	{
		$only_me = '';

		if ( $request->only_me == '1' ) $only_me = '1';
		$freeword = $request->freeword;

		$jobList =  $this->search_list($only_me ,$freeword);

		return view('comp.job_list' ,compact(
			'jobList',
			'only_me',
			'freeword',
		));
	}


/*************************************
* 一覧検索
**************************************/
	public function search_list($only_me ,$freeword)
{
		$loginUser = Auth::user();

		$jobQuery = Job::leftJoin('units', 'jobs.unit_id','=','units.id')
				->leftJoin('comp_members', 'jobs.member_id','=','comp_members.id')
				->selectRaw("jobs.* ,units.name as unit_name ,comp_members.name as member_name ,(CASE WHEN jobs.person LIKE '%$loginUser->id%' THEN 1 ELSE 0 END) as edit_flag")
				->where('jobs.company_id' , $loginUser->company_id);
	
		if ($only_me == '1') {
			$jobQuery = $jobQuery->where('jobs.person' , 'like' ,"%$loginUser->id%");
		}

		$words = array();
		if (!empty($freeword)) {
			$fd = str_replace('　', ' ',$freeword);
			$words = explode(" ", $fd);
		}

		for ($i = 0; $i < count($words); $i++) {
			$jobQuery = $jobQuery
				->where(function($query) use ($words ,$i) {
					$query->where('jobs.name' , 'like', "%{$words[$i]}%")
					->orWhere('jobs.intro' , 'like', "%{$words[$i]}%")
					->orWhere('jobs.job_code' , 'like', "%{$words[$i]}%")
					->orWhere('jobs.sub_category' , 'like', "%{$words[$i]}%")
					->orWhere('units.name' , 'like', "%{$words[$i]}%")
					->orWhere('job_cat_details.name' , 'like', "%{$words[$i]}%")
					;
				});
		}

			
		$jobList = $jobQuery->orderBy('jobs.created_at' , 'desc')->paginate(10);

		$idx = 0;
		foreach ($jobList as $list) {
			
		if ( !empty($list->person) ) {
			$loc = explode(',', $list->person);
			$ln = CompMember::whereIn('id' ,$loc)->get();

				$person_name = array();
				for ($i = 0; $i < count($ln); $i++) {
					$person_name[] = $ln[$i]['name'];
				}

				$jobList[$idx++]->person_name = implode('/', $person_name);
			} else {
				$jobList[$idx++]->person_name = '';
			}
		}

		return $jobList;
}


/*************************************
* 新規作成
**************************************/
	public function getRegister()
	{
		$loginUser = Auth::user();

		$comp_id = $loginUser->company_id;
		$edit_flag = 1;

 		$comp = Company::find($comp_id);
 		$memberList = CompMember::select('id', 'name')->where('company_id' , $comp_id)->get();
 		$unitList = Unit::select('id', 'name')->where('company_id' , $comp_id)->get();

		$job = new Job();
		$jobPr = new JobPr();

		$job->backg_flag     = $comp->backg_flag;   // 職務経歴書 デフォルトセット
		$job->backg_eng_flag = $comp->backg_eng_flag;   // 職務経歴書英語 デフォルトセット
		$job->personal_flag  = $comp->personal_flag;   // 履歴書 デフォルトセット

		return view('comp.job_edit' ,compact(
			'job',
			'jobPr',
			'unitList',
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

		$validatedData = $request->validate([
			'name'             => ['required', 'string', 'max:100'],
			'intro'             => ['required', 'string'],
			'locations'         => ['required'],
			'person'            => ['required'],
		]);

		if ($request->casual_flag == '1') {
			$casual_flag = 1;
		} else {
			$casual_flag = 0;
		}

		if ($request->remote == '1') {
			$remote_flag = 1;
		} else {
			$remote_flag = 0;
		}

		$locations = '';
		if (!empty($request->locations) ) {
			$locations = implode(',', $request->locations);
		}

		// 職種保存
		if (!empty($request->jobCat)) {
			$temp = $request->jobCat;

			$cats_list = array();
			for ($i = 0 ; $i < count($temp); $i++) {
				$cats_list[] = '[' . $temp[$i] . ']';
			}
			$job_cat_details = implode(',', $cats_list);

			// 職種カテゴリ
			$parList = JobCatDetail::whereIn('id' ,$temp)
				->selectRaw('distinct job_cat_id')
				->get();

			$catList  = array();
			foreach ($parList as $par) {
				$catList[] =  '[' . $par->job_cat_id . ']';
			}
			$job_cats = implode(',', $catList);

		} else {
			$job_cats = null;
			$job_cat_details = null;
		}

		// インダストリ保存
		if (!empty($request->indCat)) {
			$temp = $request->indCat;

			$cats_list = array();
			for ($i = 0 ; $i < count($temp); $i++) {
				$cats_list[] = '[' . $temp[$i] . ']';
			}

			$industory_cat_details = implode(',', $cats_list);

			// インダストリカテゴリ
			$parList = IndustoryCatDetail::whereIn('id' ,$temp)
				->selectRaw('distinct industory_cat_id')
				->get();

			$catList  = array();
			foreach ($parList as $par) {
				$catList[] =  '[' . $par->industory_cat_id . ']';
			}
			$industory_cats = implode(',', $catList);

		} else {
			$job->industory_cats = null;
			$job->industory_cat_details = null;
		}


		$retJob = Job::updateOrCreate(
			['id' => $request->job_id],
			['company_id'           => $comp_id,
			'unit_id'               => $request->unit,
			'member_id'             => $loginUser->id,
			'name'                  => $request->name,
			'intro'                 => $request->intro,
			'job_code'              => $request->job_code,
			'sub_category'          => $request->sub_category,
			'locations'             => $locations,
			'else_location'         => $request->else_location,
			'remote_flag'           => $remote_flag,
			'backg_flag'            => $request->backg,
			'backg_eng_flag'        => $request->backg_eng,
			'personal_flag'         => $request->personal,
			'casual_flag'           => $casual_flag,
			'person'                => $request->person,
			'working_place'         => $request->working_place,
			'job_cats'              => $job_cats,
			'job_cat_details'       => $job_cat_details,
			'industory_cats'        => $industory_cats,
			'industory_cat_details' => $industory_cat_details,
			]
		);

/*
		$cnt = count($request->job_pr_id);
		for ($i = 0; $i < $cnt; $i++) {
			$pr_id = $request->job_pr_id[$i];
			$headline = $request->headline[$i];
			$content = $request->content[$i];

			JobPr::updateOrCreate(
				['id' => $pr_id],
				['job_id' => $retJob->id, 'company_id' => $comp_id, 'headline' => $headline, 'content' => $content]
			);
		}
*/
		return redirect()->route('comp.job.edit', [ 'job_id' => $retJob->id ] )->with('update_success', 'ジョブ情報を保存しました。');
	}


/*************************************
* 編集
**************************************/
	public function edit( Request $request )
	{
		$loginUser = Auth::user();

		$comp_id = $loginUser->company_id;

 		$memberList = CompMember::select('id', 'name')->where('company_id' , $comp_id)->get();
 		$unitList = Unit::select('id', 'name')->where('company_id' , $comp_id)->get();

		$job = Job::find($request->job_id);
		$jobPr = JobPr::select()->where('job_id' , $job->id)->get();

//		$edit_flag = $request->edit_flag;
		
		return view('comp.job_edit' ,compact(
			'job',
			'jobPr',
			'unitList',
			'memberList',
//			'edit_flag',
		));
	}


/*************************************
* 状態変更
**************************************/
	public function change( Request $request )
	{
		$loginUser = Auth::user();

		$job = Job::find($request->job_id);

		if ($request->del_flag == '1') {
			$job->delete();
			
		} else {
			if ($request->open_flag == '1') {
				if ($job->open_flag == '0') $job->open_date = date("Y-m-d H:i:s");
				$job->open_flag = 1;
			} else {
				$job->open_flag = 0;
			}
			$job->save();
		}

		if ($request->del_flag == '1') {
			return redirect('comp/job');
		} else {
			return redirect()->route('comp.job.edit', [ 'job_id' => $job->id ] )->with('option_success', 'ジョブ情報を保存しました。');
		}
	
	}


}
