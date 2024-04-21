<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Models\BlogCat;
use App\Models\BlogSupervisor;

class Blog extends Model
{
	use SoftDeletes;

//     protected $table = 'blogs';

	protected $guarded = [
        'id',
    ];


/*************************************
* ���ƥ���̾����
**************************************/
	public function getCatName() {

		$result = '';

		if (!empty($this->cat_id)) {
			$temp = BlogCat::find($this->cat_id);
			
			$result = $temp->name;
		}

		return $result;
	}


/*************************************
* �ƽ��Լ���
**************************************/
	public function getSuper()
	{
		$result = null;

		if (!empty($this->supervisor)) {
			$result = BlogSupervisor::find($this->supervisor);
		}

		return $result;
	}



}
