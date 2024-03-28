<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class JobPr extends Model
{
	use SoftDeletes;
   
//     protected $table = 'job_prs';


	protected $guarded = [
        'id',
    ];

}
