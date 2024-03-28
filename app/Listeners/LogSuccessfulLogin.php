<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Login;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
//use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

use App\Models\Company;
use App\Models\Logging;

class LogSuccessfulLogin
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(Request $request)
    {
        //
        $this->request = $request;
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle(Login $event)
    {
        // usersテーブルのupdated_atカラムのみを更新します
        $loginUser = $event->user;

        // ログオン記録を残します
        $model = new Logging();
        
//\Log::info($loginUser);
        if ( empty($loginUser->company_id) ) {
			if ( empty($loginUser->nick_name) ) {
            	$model->mode_type = 'A';
			} else {
            	$model->mode_type = 'U';
			}
            $model->login_id = $loginUser->id;
	        $model->name = $loginUser->name;
        } else {
            $model->mode_type = 'C';
			$model->login_id = $loginUser->company_id;
			$model->sub_id = $loginUser->id;
			$model->sub_name = $loginUser->name;
			$comp = Company::select('name')->find($loginUser->company_id);
	        if (!empty($comp)) $model->name = $comp->name;
        }
        
        $model->ip = request()->ip();
        $model->user_agent = request()->header('User-Agent');
        $model->save();


    }
    

}
