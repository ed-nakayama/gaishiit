<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EventPr extends Model
{
	use SoftDeletes;
   
//     protected $table = 'event_prs';


	protected $guarded = [
        'id',
    ];

}
