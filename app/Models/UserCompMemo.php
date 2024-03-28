<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserCompMemo extends Model
{
	use SoftDeletes;
   
//     protected $table = 'user_comp_memos';

	protected $guarded = [
        'id',
    ];

}
