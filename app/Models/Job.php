<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Models\Company;
use App\Models\Income;
use App\Models\ConstLocation;
use App\Models\JobCat;
use App\Models\JobCatDetail;
use App\Models\IndustoryCat;
use App\Models\IndustoryCatDetail;

class Job extends Model
{
	use SoftDeletes;
   
//     protected $table = 'jobs';

	protected $guarded = [
        'id',
    ];


/*************************************
* 企業ID取得
**************************************/
	public function getCompanyId()
	{
		$result = null;

		if (!empty($this->company_id)) {
			$temp = Company::find($this->company_id);
			$result = $temp->id;
		}

		return $result;
	}


 /*****************************************
 * 企業ランキング 取得
 ******************************************/
	public function getCompanyRanking() {

		$ranking = '';

		if (!empty($this->company_id)) {
			$ranking = Ranking::find($this->company_id);
		}
		
		return $ranking;
	}


/*************************************
* 年収取得
**************************************/
	public function getIncome()
	{
		$income  = '';

		if (!empty($this->income_id)) {
			$temp = Income::find($this->income_id);
			$income = $temp->name;
		}

		return $income;
	}


/*************************************
* 勤務地取得
**************************************/
	public function getLocations()
	{
		$retLoc = '';

		if (!empty($this->locations)) {

			$loc = explode(",", $this->locations);

			$str = array();
			for ($i = 0; $i < count($loc); $i++) {
				$temp = ConstLocation::find($loc[$i]);

				if (!empty($temp->name)) $str[] = $temp->name;
			}

			$retLoc = implode("／", $str);
		}

		return $retLoc;
	}


 /*****************************************
 * 職種カテゴリ＆職種 セット
 ******************************************/
	public function setJobCat($ary) {

		if (!empty($ary[0])) {

			$job_cats = array();
			for ($i = 0 ; $i < count($ary); $i++) {
				$job_cats[] = '[' . $ary[$i] . ']';
			}
			$this->job_cat_details = implode(',', $job_cats);

			// 職種カテゴリ
			$parList = JobCatDetail::whereIn('id' ,$ary)
				->selectRaw('distinct job_cat_id')
				->get();

			$catList  = array();
			foreach ($parList as $par) {
				$catList[] =  '[' . $par->job_cat_id . ']';
			}
			$this->job_cats = implode(',', $catList);

		} else {
			$this->job_cats = null;
			$this->job_cat_details = null;
		}

	}


 /*****************************************
 * 職種カテゴリ 配列取得
 ******************************************/
	public function getJobCat() {

		$ret = '';
		
		if (!empty($this->job_cats)) {
			$ret = str_replace('[', '', $this->job_cats);
			$ret = str_replace(']', '', $ret);
			$ret = explode(',', $ret);
		}

		return $ret;
	}


 /*****************************************
 * 職種カテゴリ名 取得
 ******************************************/
	public function getJobCatName() {

		$result = '';
		
		if (!empty($this->job_cats)) {
			$ret = str_replace('[', '', $this->job_cats);
			$ret = str_replace(']', '', $ret);
			$ret = explode(',', $ret);

			$catList = JobCat::whereIn('id' ,$ret)
				->get();
			
			if (!empty($catList[0])) {
				foreach ($catList as $cat) {
					$temp[] = $cat->name;
				}
		
				$result = implode('／', $temp);
			}
		}

		return $result;
	}


 /*****************************************
 * 職種 配列取得
 ******************************************/
	public function getJobCategory() {

		$ret = '';
		
		if (!empty($this->job_cat_details)) {
			$ret = str_replace('[', '', $this->job_cat_details);
			$ret = str_replace(']', '', $ret);
			$ret = explode(',', $ret);
		}

		return $ret;
	}


 /*****************************************
 * 職種名 取得
 ******************************************/
	public function getJobCategoryName() {

		$result = '';
		
		if (!empty($this->job_cat_details)) {
			$ret = str_replace('[', '', $this->job_cat_details);
			$ret = str_replace(']', '', $ret);
			$ret = explode(',', $ret);

			$catList = JobCatDetail::whereIn('id' ,$ret)
				->get();
			
			if (!empty($catList[0])) {
				foreach ($catList as $cat) {
					$temp[] = $cat->name;
				}
		
				$result = implode('／', $temp);
			}
		}

		return $result;
	}


 /*****************************************
 * インダストリカテゴリ＆インダストリ セット
 ******************************************/
	public function setIndustory($ary) {

		if (!empty($ary[0])) {

			$industory_cats = array();
			for ($i = 0 ; $i < count($ary); $i++) {
				$industory_cats[] = '[' . $ary[$i] . ']';
			}
			$this->industory_cat_details = implode(',', $industory_cats);

			// インダストリカテゴリ
			$parList = IndustoryCatDetail::whereIn('id' ,$ary)
				->selectRaw('distinct industory_cat_id')
				->get();

			$catList  = array();
			foreach ($parList as $par) {
				$catList[] =  '[' . $par->industory_cat_id . ']';
			}
			$this->industory_cats = implode(',', $catList);

		} else {
			$this->industory_cats = null;
			$this->industory_cat_details = null;
		}

	}


 /*****************************************
 * インダストリカテゴリ 配列取得
 ******************************************/
	public function getIndcatCat() {

		$ret = '';
		
		if (!empty($this->industory_cats)) {
			$ret = str_replace('[', '', $this->industory_cats);
			$ret = str_replace(']', '', $ret);
			$ret = explode(',', $ret);
		}

		return $ret;
	}


 /*****************************************
 * インダストリカテゴリ名 取得
 ******************************************/
	public function getIndCatName() {

		$result = '';
		
		if (!empty($this->industory_cats)) {
			$ret = str_replace('[', '', $this->industory_cats);
			$ret = str_replace(']', '', $ret);
			$ret = explode(',', $ret);

			$catList = IndustoryCat::whereIn('id' ,$ret)
				->get();
			
			if (!empty($catList[0])) {
				foreach ($catList as $cat) {
					$temp[] = $cat->name;
				}
		
				$result = implode('／', $temp);
			}
		}

		return $result;
	}


 /*****************************************
 * インダストリ 配列取得
 ******************************************/
	public function getIndustory() {

		$ret = '';
		
		if (!empty($this->industory_cat_details)) {
			$ret = str_replace('[', '', $this->industory_cat_details);
			$ret = str_replace(']', '', $ret);
			$ret = explode(',', $ret);
		}

		return $ret;
	}


 /*****************************************
 * インダストリ名 取得
 ******************************************/
	public function getIndustoryName() {

		$result = '';
		
		if (!empty($this->industory_cat_details)) {
			$ret = str_replace('[', '', $this->industory_cat_details);
			$ret = str_replace(']', '', $ret);
			$ret = explode(',', $ret);

			$catList = IndustoryCatDetail::whereIn('id' ,$ret)
				->get();
			
			if (!empty($catList[0])) {
				foreach ($catList as $cat) {
					$temp[] = $cat->name;
				}
			
				$result = implode('／', $temp);
			}
		}

		return $result;
	}



}
