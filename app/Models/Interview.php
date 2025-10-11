<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Kyslik\ColumnSortable\Sortable;

class Interview extends Model
{
	use SoftDeletes;
	use Sortable;

//     protected $table = 'interviews';

	protected $guarded = [
        'id',
    ];

	public $sortable = ['id','aprove_flag'];  //追記(ソートに使うカラムを指定


/*************************************
* interview_kind 取得
**************************************/
	public function getKind()
	{
		$result = null;

		if ($this->interview_kind == '0') {
			$result = '企業';
		} else if ($this->interview_kind == '1') {
			$result = '部署';
		} else {
			$result = 'ジョブ';
		}

		return $result;
	}


}
