<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SearchHist extends Model
{
	use SoftDeletes;
   
//     protected $table = 'search_hists';


	protected $guarded = [
        'id',
    ];

}
