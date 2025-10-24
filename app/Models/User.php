<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

//use App\Notifications\User\PasswordResetNotification;
use App\Notifications\User\PasswordResetNotification;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Models\ConstJobChange;
use App\Models\Income;
use App\Models\Company;
use App\Models\ConstEnglish;
use App\Models\ConstPref;

class User extends Authenticatable
{

	use SoftDeletes;
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
	protected $guarded = [
        'id',
    ];
    
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    
      /**
     * パスワードリセット通知の送信をオーバーライド
     *
     * @param  string  $token
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
      $this->notify(new PasswordResetNotification($token));
    }

	/*
	* 転職希望時期 取得
	*/
    public function getChangeTime()
    {
        $ret = '';
        
        if (!empty($this->change_time)) {
			$jobChange = ConstJobChange::find($this->change_time);

			$ret = $jobChange->name;
		}
	
		return $ret;
    }


	/*
	* 希望年収 取得
	*/
    public function getIncome()
    {
        $ret = '';
        
        if (!empty($this->income)) {
			$income = Income::find($this->income);

			$ret = $income->name;
		}
	
		return $ret;
    }


	/*
	* 非表示企業 取得
	*/
    public function getNoCompany()
    {
        $ret = '';

        if (!empty($this->no_company)) {

			$compName = array();
			$comps = explode(",", $this->no_company);
			$compList = Company::select('name')->whereIn('id' ,$comps)->get();
			foreach ($compList as $comp) {
				$compName[] = $comp->name;
			}
			$ret = implode("/", $compName);
		}
	
		return $ret;
    }


	/*
	* 年齢 取得
	*/
    public function getAge()
    {
        $ret = '';

        if (!empty($this->birthday)) {

			$now = date('Ymd');
			$birthday = str_replace("-", "", $this->birthday);
			
			$ret = floor(($now - $birthday) / 10000);
		}
	
		return $ret;
    }


	/*
	* 性別 取得
	*/
    public function getSex()
    {
        $ret = '';

        if ($this->sex == '1') {
            $ret = '男';
		} else if ($this->sex == '2') {
			$ret = '女';
		} else {
			$ret = '選択しない';
		}
		
		return $ret;
    }


	/*
	* 希望勤務地　取得
	*/
    public function getLocation()
    {
        $ret = '';

		// 勤務地の取得
		$locName = array();
		$locs = explode(",", $this->request_location);
		$locList = ConstLocation::select('name')->whereIn('id' ,$locs)->get();
		foreach ($locList as $loc) {
			$locName[] = $loc->name;
		}
		$ret = join(" / " ,$locName);
		
		return $ret;
    }


	/*
	* 転職を希望する業種　取得
	*/
    public function getBusDetail()
    {
        $ret = '';

		// 業種名の取得
		$catName = array();
		$cats = explode(",", $this->business_cats);
		$catList = BusinessCatDetail::select('name')->whereIn('id' ,$cats)->get();
		foreach ($catList as $cat) {
			$catName[] = $cat->name;
		}
		$ret = join(" / ",$catName);
		
		return $ret;
    }


	/*
	* 転職を希望する職種　取得
	*/
    public function getCatDetail()
    {
        $ret = '';

		$catDetailName = array();
		$cat_details = explode(",", $this->job_cat_details);
		$catDetailList = JobCatDetail::select('name')->whereIn('id' ,$cat_details)->get();
		foreach ($catDetailList as $cat) {
			$catDetailName[] = $cat->name;
		}
		$ret = join(" / ",$catDetailName);
		
		return $ret;
    }


	/*
	* 英語力　取得
	*/
    public function getEngishAbility()
    {
        $ret = '';

        if (!empty($this->english)) {
			$ability = ConstEnglish::find($this->english);

			$ret = $ability->name;
		}
		
		return $ret;
    }


	/*
	* 日本語力　取得
	*/
    public function getJapaneseAbility()
    {
        $ret = '';

        if (!empty($this->japanese)) {
			$ability = ConstEnglish::find($this->japanese);

			$ret = $ability->name;
		}
		
		return $ret;
    }


	/*
	* 県名　取得
	*/
    public function getPref()
    {
        $ret = '';

        if (!empty($this->pref)) {
			$pref = ConstPref::find($this->pref);

			$ret = $pref->name;
		}
		
		return $ret;
    }


	/*
	* 承認情報 取得
	*/
    public function getAprove()
    {
        $ret = '';

        if ($this->aprove_flag == '1') {
            $ret = '承認済';
		} else if ($this->aprove_flag == '2') {
			$ret = 'リジェクト';
		} else {
			$ret = '未承認';
		}
		
		return $ret;
    }


	/*
	* 生年月日 取得
	*/
    public function getBirthday()
    {
        $ret = str_replace('-','/', substr($this->birthday, 0 ,10));
		
		return $ret;
    }


	/*
	* 在職期間 取得
	*/
    public function getEnroll()
    {
        $ret = "{$this->enroll_from_year}年{$this->enroll_from_month}月{$this->enroll_from_day}日～";
        
		if ($this->enroll_to_year == '0') {
			$ret .= "現在";
		} else if ($this->enroll_to_year == '') {
		} else {
			$ret .= "{$this->enroll_to_year}年{$this->enroll_to_month}月{$this->enroll_to_day}日";
		}
		
		return $ret;
    }


	/*
	* 配偶者 取得
	*/
    public function getSpouse()
    {
        $ret = '';

        if ($this->spouse == '1') {
            $ret = 'あり';
		} else {
			$ret = 'なし';
		}
		
		return $ret;
    }


	/*
	* 配偶者の扶養義務 取得
	*/
    public function getObligation()
    {
        $ret = '';

        if ($this->obligation == '1') {
            $ret = 'あり';
		} else {
			$ret = 'なし';
		}
		
		return $ret;
    }


	/*
	* 現在職種 取得
	*/
    public function getCurrentJob()
    {
        $ret = '';

		if ($this->job == 1) {
			$ret = "IC";
		} else if ($this->job == '2') {
			$ret = "Management　　　　年数 {$this->mgr_year}年 / 人数 {$this->mgr_member}人";
		} else {
			$ret = "未設定";
		}


		return $ret;
    }


}
