<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
//use Illuminate\Database\Eloquent\SoftDeletes;

use App\Models\Admin;
use App\Models\User;

class AgentMailHist extends Model
{
//	use SoftDeletes;

//     protected $table = 'agent_mail_hists';

	protected $guarded = [
        'id',
    ];


/*************************************
* ユーザ名取得
**************************************/
	public function getUserName() {

		$result = '';

		if (!empty($this->user_id)) {
			$user = User::find($this->user_id);
			
			$result = $user->name;
		}

		return $result;
	}

/*************************************
* エージェント名取得
**************************************/
	public function getAgentName() {

		$result = '';

		if (!empty($this->agent_id)) {
			$admin = Admin::find($this->agent_id);
			
			$result = $admin->name;
		}

		return $result;
	}


}
