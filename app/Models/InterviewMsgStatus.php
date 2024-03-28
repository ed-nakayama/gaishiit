<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class InterviewMsgStatus extends Model
{
	use SoftDeletes;

//     protected $table = 'interview_msg_statuses';

	protected $guarded = [
        'id',
    ];

}
