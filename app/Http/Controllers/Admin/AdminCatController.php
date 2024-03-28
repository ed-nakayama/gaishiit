<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log; 
use Illuminate\Validation\Rule;

use App\Http\Controllers\Controller;
use App\Models\BusinessCat;
use App\Models\BusinessCatDetail;
use App\Models\JobCat;
use App\Models\JobCatDetail;
use App\Models\IndustoryCat;
use App\Models\IndustoryCatDetail;
use App\Models\CommitCat;
use App\Models\CommitCatDetail;


class AdminCatController extends Controller
{
	public $loginUser;
	
	public function __construct()
	{
 		$this->middleware('auth:admin');
	}


	/*************************************
	* 業種初期表示
	**************************************/
	public function buscatIndex()
	{
		$catList = BusinessCat::orderBy('order_num')->orderBy('id')->get();

		return view('admin.buscat_list' ,compact(
			'catList',
		));
	}

	/*************************************
	* 業種データ追加
	**************************************/
	public function buscatAdd( Request $request ){


		$validatedData = $request->validate([
			'solo_bus_name'     => ['required', 'string', 'max:60'],
		]);

		$cat = new BusinessCat();

        $retCat = BusinessCat::create([
            'name' => $request->solo_bus_name,
        ]);

		return redirect('admin/buscat');
    }


	/*************************************
	* 業種保存
	**************************************/
	public function buscatStore(Request $request)
	{

		$validatedData = $request->validate([
			'name'     => ['required', 'string', 'max:60'],
    		]);

		$cat = BusinessCat::find($request->cat_id);

		$cat->name =  $request->name;

		if (!empty($request->order_num)) {
			$cat->order_num = $request->order_num;
		} else {
			$cat->order_num = null;
		}
		
		if (!empty($request->del_flag)) {
			$cat->del_flag = '1';
		} else {
			$cat->del_flag = '0';
		}
		
		$cat->save();

		return redirect('admin/buscat');
	}


	/*************************************
	* 業種一覧
	**************************************/
	public function  buscatDetailList(Request $request)
	{
		
		if (empty($request->buscat_id) ) {
			$bus_cat = BusinessCat::where('del_flag','0')->first();
		} else {
			$bus_cat = BusinessCat::find($request->buscat_id);
		}
		
		$catList = BusinessCatDetail::where('business_cat_id',$bus_cat->id )->get();

		return view('admin.buscat_detail' ,compact(
			'bus_cat',
			'catList',
		));
	}


	/*************************************
	* 業種データ追加
	**************************************/
	public function buscatDetailAdd( Request $request ){


		$validatedData = $request->validate([
			'solo_bus_name'     => ['required', 'string', 'max:100'],
		]);

		$cat = new BusinessCatDetail();

        $retCat = BusinessCatDetail::create([
            'business_cat_id' => $request->buscat_id,
            'name' => $request->solo_bus_name,
        ]);

		$buscat_id = $request->buscat_id;

 		return redirect()->route('admin.buscat_detail.list', ['buscat_id' => $buscat_id ]);
   }


	/*************************************
	* 業種保存
	**************************************/
	public function busCatDetailStore(Request $request)
	{

		$validatedData = $request->validate([
			'name' => ['required', 'string', 'max:100'],
   		]);

		$cat = BusinessCatDetail::find($request->buscatdetail_id);

		$cat->name =  $request->name;

		if (!empty($request->order_num)) {
			$cat->order_num = $request->order_num;
		} else {
			$cat->order_num = null;
		}
		
		if (!empty($request->del_flag)) {
			$cat->del_flag = '1';
		} else {
			$cat->del_flag = '0';
		}
		
		$cat->save();

		$buscat_id = $request->buscat_id;

 		return redirect()->route('admin.buscat_detail.list', ['buscat_id' => $buscat_id ]);
	}



	/*************************************
	* 職種初期表示
	**************************************/
	public function jobcatIndex()
	{
		$catList = JobCat::get();

		return view('admin.jobcat_list' ,compact(
			'catList',
		));
	}

	/*************************************
	* 職種データ追加
	**************************************/
	public function jobcatAdd( Request $request ){


		$validatedData = $request->validate([
			'solo_job_name'     => ['required', 'string', 'max:60'],
		]);

		$cat = new JobCat();

        $retCat = JobCat::create([
            'name' => $request->solo_job_name,
        ]);

		return redirect('admin/jobcat');
    }


	/*************************************
	* 職種保存
	**************************************/
	public function jobcatStore(Request $request)
	{

		$validatedData = $request->validate([
			'name'     => ['required', 'string', 'max:60'],
   		]);

		$cat = JobCat::find($request->cat_id);

		$cat->name =  $request->name;

		if (!empty($request->order_num)) {
			$cat->order_num = $request->order_num;
		} else {
			$cat->order_num = null;
		}
		
		if (!empty($request->del_flag)) {
			$cat->del_flag = '1';
		} else {
			$cat->del_flag = '0';
		}
		
		$cat->save();

		return redirect('admin/jobcat');
	}




	/*************************************
	* 職種一覧
	**************************************/
	public function  jobcatDetailList(Request $request)
	{
		if (empty($request->jobcat_id) ) {
			$job_cat = JobCat::where('del_flag','0')->first();
		} else {
			$job_cat = JobCat::find($request->jobcat_id);
		}

		$catList = JobCatDetail::where('job_cat_id',$job_cat->id )->get();

		return view('admin.jobcat_detail' ,compact(
			'job_cat',
			'catList',
		));
	}


	/*************************************
	* 職種データ追加
	**************************************/
	public function jobcatDetailAdd( Request $request ){

		$validatedData = $request->validate([
			'solo_job_name'     => ['required', 'string', 'max:100'],
		]);

		$cat = new JobCatDetail();

        $retCat = JobCatDetail::create([
            'job_cat_id' => $request->jobcat_id,
            'name' => $request->solo_job_name,
        ]);

		$jobcat_id = $request->jobcat_id;

 		return redirect()->route('admin.jobcat_detail.list', ['jobcat_id' => $jobcat_id ]);
   }


	/*************************************
	* 職種保存
	**************************************/
	public function jobCatDetailStore(Request $request)
	{

		$validatedData = $request->validate([
			'name' => ['required', 'string', 'max:100'],
   		]);

		$cat = JobCatDetail::find($request->jobcatdetail_id);

		$cat->name =  $request->name;

		if (!empty($request->order_num)) {
			$cat->order_num = $request->order_num;
		} else {
			$cat->order_num = null;
		}
		
		if (!empty($request->del_flag)) {
			$cat->del_flag = '1';
		} else {
			$cat->del_flag = '0';
		}
		
		$cat->save();

		$jobcat_id = $request->jobcat_id;

 		return redirect()->route('admin.jobcat_detail.list', ['jobcat_id' => $jobcat_id ]);
	}


	/*************************************
	* インダストリ初期表示
	**************************************/
	public function industoryCatIndex()
	{
		$catList = IndustoryCat::get();

		return view('admin.industorycat_list' ,compact(
			'catList',
		));
	}

	/*************************************
	* インダストリデータ追加
	**************************************/
	public function industoryCatAdd( Request $request ){


		$validatedData = $request->validate([
			'name'     => ['required', 'string', 'max:60'],
		]);

		$cat = new IndustoryCat();

        $retCat = IndustoryCat::create([
            'name' => $request->name,
        ]);

		return redirect('admin/industorycat');
    }


	/*************************************
	* インダストリ保存
	**************************************/
	public function industoryCatStore(Request $request)
	{

		$validatedData = $request->validate([
			'name'     => ['required', 'string', 'max:60'],
   		]);

		$cat = IndustoryCat::find($request->cat_id);

		$cat->name =  $request->name;

		if (!empty($request->order_num)) {
			$cat->order_num = $request->order_num;
		} else {
			$cat->order_num = null;
		}
		
		if (!empty($request->del_flag)) {
			$cat->del_flag = '1';
		} else {
			$cat->del_flag = '0';
		}
		
		$cat->save();

		return redirect('admin/industorycat');
	}




	/*************************************
	* インダストリ一覧
	**************************************/
	public function  industoryCatDetailList(Request $request)
	{
		
		if (empty($request->cat_id) ) {
			$indCat = IndustoryCat::where('del_flag','0')->first();
		} else {
			$indCat = IndustoryCat::find($request->cat_id);
		}
		
		$catList = IndustoryCatDetail::where('industory_cat_id', $indCat->id )->get();

		return view('admin.industorycat_detail' ,compact(
			'indCat',
			'catList',
		));
	}


	/*************************************
	* インダストリデータ追加
	**************************************/
	public function industoryCatDetailAdd( Request $request ){

		$validatedData = $request->validate([
			'solo_name'     => ['required', 'string', 'max:100'],
		]);

		$cat = new IndustoryCatDetail();

        $retCat = IndustoryCatDetail::create([
            'industory_cat_id' => $request->cat_id,
            'name' => $request->solo_name,
        ]);

		$cat_id = $request->cat_id;

 		return redirect()->route('admin.industorycat_detail.list', ['cat_id' => $cat_id ]);
   }


	/*************************************
	* インダストリ保存
	**************************************/
	public function industoryCatDetailStore(Request $request)
	{

		$validatedData = $request->validate([
			'name' => ['required', 'string', 'max:100'],
   		]);

		$cat = IndustoryCatDetail::find($request->industorydetail_id);

		$cat->name =  $request->name;

		if (!empty($request->order_num)) {
			$cat->order_num = $request->order_num;
		} else {
			$cat->order_num = null;
		}
		
		if (!empty($request->del_flag)) {
			$cat->del_flag = '1';
		} else {
			$cat->del_flag = '0';
		}
		
		$cat->save();

		$cat_id = $request->industorycat_id;

 		return redirect()->route('admin.industorycat_detail.list', ['cat_id' => $cat_id ]);
	}


	/*************************************
	* こだわり初期表示
	**************************************/
	public function commitCatIndex()
	{
		$catList = CommitCat::get();

		return view('admin.commitcat_list' ,compact(
			'catList',
		));
	}

	/*************************************
	* こだわり追加
	**************************************/
	public function commitCatAdd( Request $request ){


		$validatedData = $request->validate([
			'name'     => ['required', 'string', 'max:60'],
		]);

		$cat = new CommitCat();

        $retCat = CommitCat::create([
            'name' => $request->name,
        ]);

		return redirect('admin/commitcat');
    }


	/*************************************
	* こだわり保存
	**************************************/
	public function commitCatStore(Request $request)
	{

		$validatedData = $request->validate([
			'name'     => ['required', 'string', 'max:60'],
   		]);

		$cat = CommitCat::find($request->cat_id);

		$cat->name =  $request->name;

		if (!empty($request->order_num)) {
			$cat->order_num = $request->order_num;
		} else {
			$cat->order_num = null;
		}
		
		if (!empty($request->del_flag)) {
			$cat->del_flag = '1';
		} else {
			$cat->del_flag = '0';
		}
		
		$cat->save();

		return redirect('admin/commitcat');
	}




	/*************************************
	* こだわり詳細一覧
	**************************************/
	public function  commitCatDetailList(Request $request)
	{

		if (empty($request->cat_id) ) {
			$indCat = CommitCat::where('del_flag','0')->first();
		} else {
			$indCat = CommitCat::find($request->cat_id);
		}
		
		$catList = CommitCatDetail::where('commit_cat_id', $indCat->id )->get();

		return view('admin.commitcat_detail' ,compact(
			'indCat',
			'catList',
		));
	}


	/*************************************
	* こだわり詳細追加
	**************************************/
	public function commitCatDetailAdd( Request $request ){

		$validatedData = $request->validate([
			'solo_name'     => ['required', 'string', 'max:100'],
		]);

		$cat = new CommitCatDetail();

        $retCat = CommitCatDetail::create([
            'commit_cat_id' => $request->cat_id,
            'name' => $request->solo_name,
        ]);

		$cat_id = $request->cat_id;

 		return redirect()->route('admin.commitcat_detail.list', ['cat_id' => $cat_id ]);
   }


	/*************************************
	* こだわり詳細保存
	**************************************/
	public function commitCatDetailStore(Request $request)
	{
		$validatedData = $request->validate([
			'name' => ['required', 'string', 'max:100'],
		]);

		$cat = CommitCatDetail::find($request->commitdetail_id);

		$cat->name =  $request->name;

		if (!empty($request->order_num)) {
			$cat->order_num = $request->order_num;
		} else {
			$cat->order_num = null;
		}

		if (!empty($request->index_flag)) {
			$cat->index_flag = '1';
		} else {
			$cat->index_flag = '0';
		}
		
		if (!empty($request->del_flag)) {
			$cat->del_flag = '1';
		} else {
			$cat->del_flag = '0';
		}
		
		$cat->save();

		$cat_id = $request->commitcat_id;

 		return redirect()->route('admin.commitcat_detail.list', ['cat_id' => $cat_id ]);
	}




}
