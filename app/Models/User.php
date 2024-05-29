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


}
