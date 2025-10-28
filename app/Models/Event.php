<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Event extends Model
{
	use SoftDeletes;

//     protected $table = 'events';

	protected $guarded = [
        'id',
    ];


 /*****************************************
 * Ã´Åö¼Ô ¼èÆÀ
 ******************************************/
	public function getPerson() {

		$result = '';
		
		if (!empty($this->person)) {
			$ret = explode(',', $this->person);

			$memList = CompMember::whereIn('id' ,$ret)
				->get();
			
			if (!empty($memList[0])) {
				foreach ($memList as $mem) {
					$temp[] = $mem->name;
				}
			
				$result = implode('¡¿', $temp);
			}
		}

		return $result;
	}


}
