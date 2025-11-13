<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Rpa extends Model
{
	use SoftDeletes;

//     protected $table = 'rpas';

	protected $guarded = [
        'id',
    ];



}
