<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CompUserSearch extends Model
{
	use SoftDeletes;
   
//     protected $table = 'comp_user_searches';

	protected $guarded = [
        'id',
    ];

}
