<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ClaimMonth extends Model
{

	use SoftDeletes;

//     protected $table = 'claimmonths';

	protected $guarded = [
        'id',
    ];

}
