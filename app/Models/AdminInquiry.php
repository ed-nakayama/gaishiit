<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AdminInquiry extends Model
{
	use SoftDeletes;
   
//     protected $table = 'admin_inquiries';

	protected $guarded = [
        'id',
    ];

}
