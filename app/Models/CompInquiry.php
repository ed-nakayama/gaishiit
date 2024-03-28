<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CompInquiry extends Model
{
	use SoftDeletes;
   
//     protected $table = 'comp_inquiries';

	protected $guarded = [
        'id',
    ];

}
