<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CompFaq extends Model
{
	use SoftDeletes;
   
//     protected $table = 'comp_faqs';

	protected $guarded = [
        'id',
    ];

}
