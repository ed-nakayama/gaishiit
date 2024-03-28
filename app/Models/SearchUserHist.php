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

class SearchUserHist extends Model
{
	use SoftDeletes;

//     protected $table = 'search_user_hists';

	protected $guarded = [
        'id',
    ];


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


/*************************************
* 企業名取得
**************************************/
	public function getCompanyName()
	{
		$retLoc = '';

		if (!empty($this->comps)) {

			$loc = explode(",", $this->comps);

			$str = array();
			for ($i = 0; $i < count($loc); $i++) {
				$temp = Company::find($loc[$i]);

				if (!empty($temp->name)) $str[] = $temp->name;
			}

			$retLoc = implode("／", $str);
		}

		return $retLoc;
	}


 /*****************************************
 * 職種カテゴリ セット
 ******************************************/
	public function setJobCat($comma) {

		if (!empty($comma)) {
			$ary = explode(',', $comma);

			$job_cats = array();
			for ($i = 0 ; $i < count($ary); $i++) {
				$job_cats[] = '[' . $ary[$i] . ']';
			}
			$this->job_cats = implode(',', $job_cats);

		} else {
			$this->job_cats = null;
		}

	}


 /*****************************************
 * 職種 セット
 ******************************************/
	public function setJobCategory($comma) {

		if (!empty($comma)) {
			$ary = explode(',', $comma);

			$job_cats = array();
			for ($i = 0 ; $i < count($ary); $i++) {
				$job_cats[] = '[' . $ary[$i] . ']';
			}
			$this->job_cat_details = implode(',', $job_cats);

		} else {
			$this->job_cat_details = null;
		}

	}


 /*****************************************
 * 職種カテゴリ カンマ取得
 ******************************************/
	public function getJobCat() {

		$ret = '';
		
		if (!empty($this->job_cats)) {
			$ret = str_replace('[', '', $this->job_cats);
			$ret = str_replace(']', '', $ret);
//			$ret = explode(',', $ret);
		}

		return $ret;
	}


 /*****************************************
 * 職種 カンマ取得
 ******************************************/
	public function getJobCategory() {

		$ret = '';
		
		if (!empty($this->job_cat_details)) {
			$ret = str_replace('[', '', $this->job_cat_details);
			$ret = str_replace(']', '', $ret);
//			$ret = explode(',', $ret);
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
 * インダストリカテゴリ セット
 ******************************************/
	public function setIndcat($comma) {

		if (!empty($comma)) {
			$ary = explode(',', $comma);

			$industory_cats = array();
			for ($i = 0 ; $i < count($ary); $i++) {
				$industory_cats[] = '[' . $ary[$i] . ']';
			}
			$this->industory_cats = implode(',', $industory_cats);

		} else {
			$this->industory_cats = null;
		}

	}


 /*****************************************
 * インダストリ セット
 ******************************************/
	public function setIndustory($comma) {

		if (!empty($comma)) {
			$ary = explode(',', $comma);

			$industory_cats = array();
			for ($i = 0 ; $i < count($ary); $i++) {
				$industory_cats[] = '[' . $ary[$i] . ']';
			}
			$this->industory_cat_details = implode(',', $industory_cats);

		} else {
			$this->industory_cat_details = null;
		}

	}


 /*****************************************
 * インダストリカテゴリ カンマ取得
 ******************************************/
	public function getIndcatCat() {

		$ret = '';
		
		if (!empty($this->industory_cats)) {
			$ret = str_replace('[', '', $this->industory_cats);
			$ret = str_replace(']', '', $ret);
//			$ret = explode(',', $ret);
		}

		return $ret;
	}


 /*****************************************
 * インダストリ カンマ取得
 ******************************************/
	public function getIndustory() {

		$ret = '';
		
		if (!empty($this->industory_cat_details)) {
			$ret = str_replace('[', '', $this->industory_cat_details);
			$ret = str_replace(']', '', $ret);
//			$ret = explode(',', $ret);
		}

		return $ret;
	}


 /*****************************************
 * インダストリカテゴリ名 取得
 ******************************************/
	public function getIndcatName() {

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



 /*****************************************
 * 業種カテゴリ セット
 ******************************************/
	public function setBusCat($comma) {

		if (!empty($comma)) {
			$ary = explode(',', $comma);

			$business_cats = array();
			for ($i = 0 ; $i < count($ary); $i++) {
				$business_cats[] = '[' . $ary[$i] . ']';
			}
			$this->business_cats = implode(',', $business_cats);

		} else {
			$this->business_cats = null;
		}

	}


 /*****************************************
 * 業種 セット
 ******************************************/
	public function setBusiness($comma) {

		if (!empty($comma)) {
			$ary = explode(',', $comma);

			$business_cats = array();
			for ($i = 0 ; $i < count($ary); $i++) {
				$business_cats[] = '[' . $ary[$i] . ']';
			}
			$this->business_cat_details = implode(',', $business_cats);

		} else {
			$this->business_cat_details = null;
		}

	}


 /*****************************************
 * 業種カテゴリ カンマ取得
 ******************************************/
	public function getBusCat() {

		$ret = '';
		
		if (!empty($this->business_cats)) {
			$ret = str_replace('[', '', $this->business_cats);
			$ret = str_replace(']', '', $ret);
//			$ret = explode(',', $ret);
		}

		return $ret;
	}


 /*****************************************
 * 業種 カンマ取得
 ******************************************/
	public function getBusiness() {

		$ret = '';
		
		if (!empty($this->business_cat_details)) {
			$ret = str_replace('[', '', $this->business_cat_details);
			$ret = str_replace(']', '', $ret);
//			$ret = explode(',', $ret);
		}

		return $ret;
	}


 /*****************************************
 * 業種カテゴリ名 取得
 ******************************************/
	public function getBuscatName() {

		$result = '';

		if (!empty($this->business_cats)) {
			$ret = str_replace('[', '', $this->business_cats);
			$ret = str_replace(']', '', $ret);
			$ret = explode(',', $ret);

			$catList = BusinessCat::whereIn('id' ,$ret)
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
 * 業種名 取得
 ******************************************/
	public function getBusinessName() {

		$result = '';
		
		if (!empty($this->business_cat_details)) {
			$ret = str_replace('[', '', $this->business_cat_details);
			$ret = str_replace(']', '', $ret);
			$ret = explode(',', $ret);

			$catList = BusinessCatDetail::whereIn('id' ,$ret)
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
 * こだわりセット
 ******************************************/
	public function setCommit($comma) {

		if (!empty($comma)) {
			$ary = explode(',', $comma);

			$commit_cats = array();
			for ($i = 0 ; $i < count($ary); $i++) {
				$commit_cats[] = '[' . $ary[$i] . ']';
			}
			$this->commit_cat_details = implode(',', $commit_cats);

		} else {
			$this->commit_cats = null;
		}
	}


 /*****************************************
 * こだわり カンマ取得
 ******************************************/
	public function getCommit() {

		$ret = '';
		
		if (!empty($this->commit_cat_details)) {
			$ret = str_replace('[', '', $this->commit_cat_details);
			$ret = str_replace(']', '', $ret);
//			$ret = explode(',', $ret);
		}

		return $ret;
	}


 /*****************************************
 * こだわり名称 取得
 ******************************************/
	public function getCommitName() {

		$result = '';
		
		if (!empty($this->commit_cat_details)) {
			$ret = str_replace('[', '', $this->commit_cat_details);
			$ret = str_replace(']', '', $ret);
			$ret = explode(',', $ret);

			$catList = CommitCatDetail::whereIn('id' ,$ret)
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


/*************************************
* 年収取得
**************************************/
	public function getIncome()
	{
		$income  = '';

		if (!empty($this->incomes)) {

			$loc = explode(",", $this->incomes);

			$str = array();
			for ($i = 0; $i < count($loc); $i++) {
				$temp = Income::find($loc[$i]);

				if (!empty($temp->name)) $str[] = $temp->name;
			}

			$income = implode("／", $str);
		}

		return $income;
	}


/*************************************
* 配列得
**************************************/
	public function getParam()
	{
		$param = array();
		
		$param['freeword'] = $this->freeword;
		$param['comps'] = $this->comps;
		$param['job_cats'] = $this->job_cats;
		$param['job_cat_details'] = $this->job_cat_details;
		$param['industory_cats'] = $this->industory_cats;
		$param['industory_cat_details'] = $this->industory_cat_details;
		$param['business_cats'] = $this->business_cats;
		$param['business_cat_details'] = $this->business_cat_details;
		$param['commit_cat_details'] = $this->commit_cat_details;

		if (!empty($this->locations)) {
			$param['locations'] = explode(',', $this->locations);
		} else {
			$param['locations'] = null;
		}

		if (!empty($this->incomes)) {
			$param['incomes'] = explode(',', $this->incomes);
		} else {
			$param['incomes'] = null;
		}

		return $param;
	}



}
