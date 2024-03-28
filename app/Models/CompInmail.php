<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CompInmail extends Model
{
	use SoftDeletes;

//     protected $table = 'comp_inmails';

	protected $guarded = [
        'id',
    ];


}
