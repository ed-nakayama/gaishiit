<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class InterviewMessage extends Model
{
	use SoftDeletes;

//     protected $table = 'interview_messages';

	protected $guarded = [
        'id',
    ];

}
