<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Models\BusinessCatDetail;
use App\Models\CommitCatDetail;
use App\Models\Ranking;

class Company extends Model
{
	use SoftDeletes;

//     protected $table = 'companies';

	protected $guarded = [
        'id',
    ];


 /*****************************************
 * 業種カテゴリ&業種 セット
 ******************************************/
	public function setBusCat($ary) {

		if (!empty($ary[0])) {

			$business_cats = array();
			for ($i = 0 ; $i < count($ary); $i++) {
				$business_cats[] = '[' . $ary[$i] . ']';
			}
			$this->business_cat_details = implode(',', $business_cats);

			// 業種カテゴリ
			$parList = BusinessCatDetail::whereIn('id' ,$ary)
				->selectRaw('distinct business_cat_id')
				->get();

			$catList  = array();
			foreach ($parList as $par) {
				$catList[] =  '[' . $par->business_cat_id . ']';
			}
			$this->business_cats = implode(',', $catList);

		} else {
			$this->business_cats = null;
			$this->business_cat_details = null;
		}

	}


 /*****************************************
 * 業種カテゴリ 配列取得
 ******************************************/
	public function getBusCat() {

		$ret = '';
		
		if (!empty($this->business_cats)) {
			$ret = str_replace('[', '', $this->business_cats);
			$ret = str_replace(']', '', $ret);
			$ret = explode(',', $ret);
		}

		return $ret;
	}


 /*****************************************
 * 業種カテゴリ名 取得
 ******************************************/
	public function getBusCatName() {

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
 * 業種 配列取得
 ******************************************/
	public function getBusiness() {

		$ret = '';
		
		if (!empty($this->business_cat_details)) {
			$ret = str_replace('[', '', $this->business_cat_details);
			$ret = str_replace(']', '', $ret);
			$ret = explode(',', $ret);
		}

		return $ret;
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

			if (!empty($catList[0])) {
				$catList = BusinessCatDetail::whereIn('id' ,$ret)
					->get();
			
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
	public function setCommit($ary) {

		if (!empty($ary[0])) {

			$commit_cats = array();
			for ($i = 0 ; $i < count($ary); $i++) {
				$commit_cats[] = '[' . $ary[$i] . ']';
			}
			$this->commit_cats = implode(',', $commit_cats);

		} else {
			$this->commit_cats = null;
		}
	}


 /*****************************************
 * こだわりは配列取得
 ******************************************/
	public function getCommit() {

		$ret = '';
		
		if (!empty($this->commit_cats)) {
			$ret = str_replace('[', '', $this->commit_cats);
			$ret = str_replace(']', '', $ret);
			$ret = explode(',', $ret);
		}

		return $ret;
	}


 /*****************************************
 * こだわり名称 取得
 ******************************************/
	public function getCommitName() {

		$result = '';
		
		if (!empty($this->commit_cats)) {
			$ret = str_replace('[', '', $this->commit_cats);
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


 /*****************************************
 * 企業ランキング 取得
 ******************************************/
	public function getCompanyRanking() {

		// クチコミ
		$ranking = Ranking::find($this->id);

		return $ranking;
	}

}
