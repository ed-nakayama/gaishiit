<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserComp extends Model
{
	use SoftDeletes;
   
//     protected $table = 'user_comps';

	protected $guarded = [
        'id',
    ];

}
