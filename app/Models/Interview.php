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

}
