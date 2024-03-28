<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Models\BlogCat;

class Blog extends Model
{
	use SoftDeletes;

//     protected $table = 'blogs';

	protected $guarded = [
        'id',
    ];


	public function getCatName() {

		$result = '';

		if (!empty($this->category)) {
			$temp = BlogCat::find($this->category);
			
			$result = $temp->name;
		}

		return $result;
	}

}
