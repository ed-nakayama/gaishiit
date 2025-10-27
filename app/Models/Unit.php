<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Unit extends Model
{
	use SoftDeletes;

	public $persons;

     protected $table = 'units';

	protected $guarded = [
        'id',
    ];


/*************************************
* 担当者 取得
**************************************/
	public function getPerson()
	{
		$this->persons = null;

		$loc = array();

		if ( !empty($this->person) ) {
			$loc = explode(',', $this->person);
			$ln = CompMember::whereIn('id' ,$loc)->get();

			$person_name = array();
			for ($i = 0; $i < count($ln); $i++) {
				$person_name[] = $ln[$i]['name'];
			}

			$this->persons = implode('/', $person_name);
		}

	}


}
