<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Ranking extends Model
{
	use SoftDeletes;

//     protected $table ='ranking';

	protected $guarded = [];

	protected $primaryKey = 'company_id';
	public $incrementing = false;

}
