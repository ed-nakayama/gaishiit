<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AdminInquiry extends Model
{
	use SoftDeletes;

	public $user;
   
//     protected $table = 'admin_inquiries';

	protected $guarded = [
        'id',
    ];


/*************************************
* ユーザ情報 取得
**************************************/
	public function getUser()
	{
		$this->user = null;

		if (!empty($this->user_id)) {
			$this->user = User::withTrashed()
				->find($this->user_id);
		}
	}

}
