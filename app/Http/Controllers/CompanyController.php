<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\UpdatePasswordRequest;
use Illuminate\Support\Facades\Auth;

use Illuminate\Validation\Rule;

use Illuminate\Support\Facades\Log; 

use App\Models\Company;
use App\Models\Unit;
use App\Models\UnitPr;
use App\Models\Event;
use App\Models\Job;
use App\Models\JobCat;
use App\Models\JobCatDetail;
use App\Models\Pickup;
use App\Models\Interview;
use App\Models\CompFaq;
use App\Models\Evaluation;
use App\Models\Ranking;

use Storage;
use Validator;

class CompanyController extends Controller
{
	public $loginUser;
	
	public function __construct()
	{
//		$this->middleware(['auth:user']);
	}


/*************************************
* 初期表示
**************************************/
	public function index()
	{
		$compList = Company::select('id', 'name')->where('open_flag' ,'1')->paginate(10);

		return view('company' ,compact('compList'));
	}


/*************************************
* 企業詳細
**************************************/
	public function list(Request $request)
	{
		$loginUser = Auth::guard('user')->user();
		
		$compList = Company::selectRaw('companies.*');

		$comp_sel = $request->comp_sel;

		if ( !empty($comp_sel) ) {
			$compList = $compList->whereIn( 'companies.id',[ $comp_sel ] );
		}

		$pickupList = Pickup::whereNotNull('company_id')->where('company_id','!=','')->get();
		$len = count($pickupList);
	 	if ($len == 0) {
			$pickup = '';
			
		} else {
		 	$arg = mt_rand(1, $len) - 1;
		 	$comp_id = $pickupList[$arg]->company_id;
		 	
		 	$pickup = Company::selectRaw('companies.* ')
				->where('companies.id' ,$comp_id)
				->first();
		}

		$compList = $compList->where('open_flag' ,'1')
			->orderBy('companies.updated_at' ,'desc')
			->paginate(10);

 		return view('user.company' ,compact(
 			'compList',
 			'pickup',
 			));
	}


/*************************************
* 企業詳細
**************************************/
	public function comp_detail(Request $request, $compId)
	{
		$loginUser = Auth::guard('user')->user();

		if (empty($request->more_event)) {
			$more_event = 3;
		} else {
			$more_event = $request->more_event + 3;
		}

		$comp = Company::leftJoin('rankings', 'rankings.company_id', 'companies.id')
			->where('companies.id' ,$compId)
			->where('open_flag' ,'1')
			->selectRaw('companies.*, rankings.*')
			->first();

		if (empty($comp)) {
 			return view('user.not_ready');
		}

		// イベント
		$eventCnt = Event::selectRaw('events.*  ,units.name as unit_name ')
			->where('event_kind' , '1')
			->where('events.company_id' , $comp->id)
			->whereNull('events.unit_id')
			->where('events.open_flag' , '1')
			->count();

		$eventList = Event::selectRaw('events.*')
			->where('event_kind' , '0')
			->where('events.company_id' ,$comp->id)
			->whereNull('events.unit_id')
			->where('events.open_flag' , '1')
			->orderBy('event_date' , 'desc')
			->limit($more_event)
			->get();

		// ジョブ
		$jobList = Job::Join('companies','jobs.company_id','=','companies.id')
			->selectRaw('jobs.* ')
			->where('jobs.company_id' , $comp->id)
			->where('jobs.open_flag','1')
			->whereNotNull('jobs.intro')
			->where('jobs.intro','!=','')
			->paginate(6);


		$interview = null;
		if (!empty($loginUser)) {
			$interview = Interview::where('user_id' ,$loginUser->id)
				->where('company_id' ,$comp->id)
				->where('interview_type' ,'0')
				->where('interview_kind' ,'0')
				->orderBy('created_at' ,'desc')
				->first();
//				->get();

			$my_count = Evaluation::where('company_id' ,$comp->id)
				->where('user_id' ,$loginUser->id)
				->count();
		} else {
			$my_count = 0;
		}
		
		$qa_count = CompFaq::where('company_id' ,$comp->id)
			->where('open_flag' ,'1')
			->count();


		// クチコミカテゴリ取得
		$cat = $this->get_cat();

		$ranking = Ranking::find($comp->id);

		if (!empty($ranking)) {
			$evalList = $this->get_eval($comp->id, $cat);
		} else {
			$evalList = null;
		}

 		return view('user.company_detail' ,compact(
 			'comp',
 			'eventCnt',
 			'eventList',
 			'jobList',
 			'more_event',
 			'interview',
 			'qa_count',
 			'cat',
			'ranking',
			'evalList',
			'my_count',
			));
	}


/*************************************
* カテゴリ別
**************************************/
	public function eval_category(Request $request, $compId)
	{
		$loginUser = Auth::guard('user')->user();

		$comp = Company::leftJoin('rankings', 'rankings.company_id', 'companies.id')
			->where('companies.id' ,$compId)
			->where('open_flag' ,'1')
			->selectRaw('companies.*, rankings.*')
			->first();

		if (empty($comp)) {
 			return view('user.not_ready');
		}

		// ジョブ
		$jobList = Job::Join('companies','jobs.company_id','=','companies.id')
			->selectRaw('jobs.*')
			->where('jobs.company_id' , $comp->id)
			->where('jobs.open_flag','1')
			->whereNotNull('jobs.intro')
			->where('jobs.intro','!=','')
			->paginate(6);


		$interview = null;
		if (!empty($loginUser)) {
			$interview = Interview::where('user_id' ,$loginUser->id)
				->where('company_id' ,$comp->id)
				->where('interview_type' ,'0')
				->where('interview_kind' ,'0')
				->orderBy('created_at' ,'desc')
				->first();
//				->get();

			$my_count = Evaluation::where('company_id' ,$comp->id)
				->where('user_id' ,$loginUser->id)
				->count();
		} else {
			$my_count = 0;
		}
		
		// クチコミカテゴリ取得
		$cat = $this->get_cat();

		$ranking = Ranking::find($comp->id);

		if (!empty($ranking)) {
			$evalList = $this->get_eval($comp->id, $cat);
		} else {
			$evalList = null;
		}

 		return view('user.eval_category' ,compact(
 			'comp',
 			'jobList',
 			'interview',
 			'cat',
			'ranking',
			'evalList',
			'my_count',
			));
	}


/*************************************
* カテゴリ取得
**************************************/
	private function get_cat()
	{
		$cat['sel'] = '';
		$cat['name'] = '';

		$url = url()->current();

		if (false !== strpos($url, 'salary')) { // 給与
			$cat['sel'] = '1';
			$cat['name'] = '給与';

		} elseif (false !== strpos($url, 'welfare')) { // 福利厚生
			$cat['sel'] = '2';
			$cat['name'] = '福利厚生';

		} elseif (false !== strpos($url, 'upbring')) { // 育成
			$cat['sel'] = '3';
			$cat['name'] = '育成';

		} elseif (false !== strpos($url, 'compliance')) { // 法令遵守の意識
			$cat['sel'] = '4';
			$cat['name'] = '法令遵守の意識';

		} elseif (false !== strpos($url, 'motivation')) { // 社員のモチベーション
			$cat['sel'] = '5';
			$cat['name'] = '社員のモチベーション';

		} elseif (false !== strpos($url, 'worklife')) { // ワークライフバランス
			$cat['sel'] = '6';
			$cat['name'] = 'ワークライフバランス';

		} elseif (false !== strpos($url, 'remote')) { // リモート勤務
			$cat['sel'] = '7';
			$cat['name'] = 'リモート勤務';

		} elseif (false !== strpos($url, 'retirement')) { // 定年
			$cat['sel'] = '8';
			$cat['name'] = '定年';
		}

		return $cat;
	}


/*************************************
* 評価リスト取得
**************************************/
	public function get_eval($comp_id, $cat)
	{
		if (!empty($cat['sel']) ) {
			$query = Evaluation::leftJoin('users','evaluations.user_id','users.id')
				->where('evaluations.company_id' ,$comp_id)
				->where('evaluations.approve_flag' ,'8');

			if ($cat['sel'] == '1') {
				$query = $query->whereNotNull('salary_content');
			} elseif ($cat['sel'] == '2') {
				$query = $query->whereNotNull('welfare_content');
			} elseif ($cat['sel'] == '3') {
				$query = $query->whereNotNull('upbring_content');
			} elseif ($cat['sel'] == '4') {
				$query = $query->whereNotNull('compliance_content');
			} elseif ($cat['sel'] == '5') {
				$query = $query->whereNotNull('motivation_content');
			} elseif ($cat['sel'] == '6') {
				$query = $query->whereNotNull('work_life_content');
			} elseif ($cat['sel'] == '7') {
				$query = $query->whereNotNull('remote_content');
			} elseif ($cat['sel'] == '8') {
				$query = $query->whereNotNull('retire_content');
			}
					
			$evalList = $query->selectRaw('evaluations.*, users.sex as user_sex')
				->orderBy('evaluations.updated_at' ,'desc')
				->paginate(10);

			for ($i = 0; $i < count($evalList); $i++) {
				if (isset($evalList[$i]->user_sex)) {
					$evalList[$i]->sex = $evalList[$i]->user_sex;
				}
				$evalList[$i]->cat_sel = $cat['sel'];
			}

		} else {
			$evalList = array();

			$list = Evaluation::leftJoin('users','evaluations.user_id','users.id')
					->where('evaluations.company_id' ,$comp_id)
					->where('evaluations.approve_flag' ,'8')
					->selectRaw('evaluations.*, users.sex as user_sex')
					->orderBy('evaluations.updated_at' ,'desc')
					->limit(3)
					->get();

			foreach ($list as $eval) {

				$cnt = 0;
				
				if (!empty($eval->salary_content) ) {
					$temp = clone $eval;
					$temp->sel = 1;
					$temp->point = $eval->salary_point;
					$temp->content = $eval->salary_content;
					$evalList[] = $temp;
					$cnt++;
				}

				if (!empty($eval->welfare_content) ) {
					$temp = clone $eval;
					$temp->sel = 2;
					$temp->point = $eval->welfare_point;
					$temp->content = $eval->welfare_content;
					$evalList[] = $temp;
					$cnt++;
				}

				if (!empty($eval->upbring_content) ) {
					$temp = clone $eval;
					$temp->sel = 3;
					$temp->point = $eval->upbring_point;
					$temp->content = $eval->upbring_content;
					$evalList[] = $temp;
					$cnt++;
				}
			
				if ($cnt > 3) continue;

				if (!empty($eval->compliance_content) ) {
					$temp = clone $eval;
					$temp->sel = 4;
					$temp->point = $eval->compliance_point;
					$temp->content = $eval->compliance_content;
					$evalList[] = $temp;
					$cnt++;
				}

				if ($cnt > 3) continue;

				if (!empty($eval->motivation_content) ) {
					$temp = clone $eval;
					$temp->sel = 5;
					$temp->point = $eval->motivation_point;
					$temp->content = $eval->motivation_content;
					$evalList[] = $temp;
					$cnt++;
				}

				if ($cnt > 3) continue;

				if (!empty($eval->work_life_content) ) {
					$temp = clone $eval;
					$temp->sel = 6;
					$temp->point = $eval->work_life_point;
					$temp->content = $eval->work_life_content;
					$evalList[] = $temp;
					$cnt++;
				}

				if ($cnt > 3) continue;

				if (!empty($eval->remote_content) ) {
					$temp = clone $eval;
					$temp->sel = 7;
					$temp->point = $eval->remote_point;
					$temp->content = $eval->remote_content;
					$evalList[] = $temp;
					$cnt++;
				}

				if ($cnt > 3) continue;
					
				if (!empty($eval->retire_content) ) {
					$temp = clone $eval;
					$temp->sel = 8;
					$temp->point = $eval->retire_point;
					$temp->content = $eval->retire_content;
					$evalList[] = $temp;
					$cnt++;
				}
			}
		}

		return $evalList;

	}


/*************************************
* 部署詳細
**************************************/
	public function unit_detail(Request $request, $compId, $unitId)
	{
		$loginUser = Auth::guard('user')->user();

		if (empty($request->more_event)) {
			$more_event = 3;
		} else {
			$more_event = $request->more_event + 3;
		}

		if (empty($request->more_job)) {
			$more_job = 8;
		} else {
			$more_job = $request->more_job + 8;
		}

		$unit = Unit::where('units.id' ,$unitId)
			->where('units.open_flag' ,'1')
			->first();

		if (empty($unit)) {
 			return view('user.not_ready');
		}

		if ( !empty($unit->job_cat_id) ) {
			$job_cat = explode(",", $unit->job_cat_id);
			$unit['jobcat'] = JobCatDetail::whereIn('id' ,$job_cat)->where('del_flag' ,'0')->get();
		}

		$comp = Company::leftJoin('rankings', 'rankings.company_id', 'companies.id')
			->where('companies.id' ,$unit->company_id)
			->where('open_flag' ,'1')
			->selectRaw('companies.*, rankings.*')
			->first();

		$eventCnt = Event::leftJoin('units', 'events.unit_id','=','units.id')
			->selectRaw('events.*  ,units.name as unit_name ')
			->where('event_kind' , '1')
			->where('events.company_id' , $unit->company_id)
			->where('unit_id' , $unit->id)
			->where('events.open_flag' , '1')
			->count();

		$eventList = Event::leftJoin('units', 'events.unit_id','=','units.id')
			->selectRaw('events.*  ,units.name as unit_name ')
			->where('event_kind' , '1')
			->where('events.company_id' , $unit->company_id)
			->where('unit_id' , $unit->id)
			->where('events.open_flag' , '1')
			->orderBy('event_date' , 'desc')
			->limit($more_event)
			->get();

		// 部署
		$unitList = Unit::leftJoin('job_cats','units.job_cat_id','=','job_cats.id')
			->selectRaw('units.* ,job_cats.name as job_cat_name ')
			->where('units.company_id' , $comp->id)
			->where('units.open_flag' ,'1')
			->get();

		// 部署求人一覧
		$jobList = Job::selectRaw('jobs.* ')
			->where('jobs.company_id' , $unit->company_id)
			->where('jobs.unit_id' , $unit->id)
			->where('jobs.open_flag' , '1')
			->whereNotNull('jobs.intro')
			->where('jobs.intro','!=','')
			->limit(4)
			->get();

		// その他部署求人一覧
		$elseJobList = Job::selectRaw('jobs.*')
			->where('jobs.company_id' , $unit->company_id)
			->where('jobs.unit_id', '!=' , $unit->id)
			->where('jobs.open_flag' , '1')
			->whereNotNull('jobs.intro')
			->where('jobs.intro','!=','')
			->limit(4)
			->get();

		// クチコミカテゴリ取得
		$cat = $this->get_cat();

		$ranking = Ranking::find($comp->id);

		$arg = 0;
		foreach ($jobList as $job) {
			$jobList[$arg]->company_name = $comp->name;
			$jobList[$arg]->logo_file = $comp->logo_file;
	
			// クチコミ
			if (!empty($ranking)) {
				$jobList[$arg]->total_point = $ranking->total_point;
				$jobList[$arg]->total_rate = $ranking->total_rate;
	
			} else {
				$jobList[$arg]->total_eval = 0;
				$jobList[$arg]->total_rate = 0;
			}
	
			$arg++;
	
		} // end foreach



		// その他部署求人一覧
		$arg = 0;
		foreach ($elseJobList as $job) {
			$elseJobList[$arg]->company_name = $comp->name;
			$elseJobList[$arg]->logo_file = $comp->logo_file;
	
			// クチコミ
			if (!empty($ranking)) {
				$elseJobList[$arg]->total_point = $ranking->total_point;
				$elseJobList[$arg]->total_rate = $ranking->total_rate;
	
			} else {
				$elseJobList[$arg]->total_eval = 0;
				$elseJobList[$arg]->total_rate = 0;
			}
	
			$arg++;
	
		} // end foreach



		$interview = null;
		if (!empty($loginUser)) {
			$interview = Interview::where('user_id' ,$loginUser->id)
				->where('company_id' ,$comp->id)
				->where('interview_type' ,'0')
				->where('interview_kind' ,'1')
				->orderBy('created_at' ,'desc')
				->first();

			$my_count = Evaluation::where('company_id' ,$comp->id)
				->where('user_id' ,$loginUser->id)
				->count();
		} else {
			$my_count = 0;
		}
		
 		return view('user.unit_detail' ,compact(
 			'comp',
 			'unitList',
 			'unit',
 			'eventCnt',
 			'eventList',
 			'jobList',
 			'more_event',
 			'more_job',
  			'interview',
  			'cat',
  			'ranking',
  			'my_count',
 			'elseJobList',
			));
	}


/*************************************
* クチコミデータ更新
**************************************/
	public function ranking()
	{

		$rankingList = Ranking::Join('companies','rankings.company_id','=','companies.id')
			->where('companies.open_flag' ,'1')
			->selectRaw('rankings.*, companies.name as company_name ,companies.logo_file as logo_file ,companies.image_file as image_file')
			->orderBy('total_point', 'DESC')
			->paginate(10);

		return view('user.company_ranking', compact(
			'rankingList',
		));
	}



}
