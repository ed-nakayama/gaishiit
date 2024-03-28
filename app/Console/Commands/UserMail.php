<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Models\User;
use App\Models\Company;
use App\Models\Job;

use Illuminate\Support\Facades\Mail;

use App\Mail\JobToUser;
use App\Mail\FavoriteToUser;
use App\Models\SearchUserHist;


class UserMail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:usermail';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'send alert mail to user';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $userList = User::where('aprove_flag' ,'1')
			->where(function($query) {
				$query->where('job_mail_flag'      ,'1')
					->orWhere('favorite_mail_flag' ,'1');
				})
			->get();

		foreach ($userList as $user) {

			if ($user->job_mail_flag == '1') {
				
				$searchUserHist = SearchUserHist::where('user_id' ,$user->id)->first();

				$param = $searchUserHist->getParam();

	 			$job = app()->make('App\Http\Controllers\JobController');
				$jobListOrg = $job->search_local($param );

				$cnt = count($jobListOrg);
				
				
				$jobList = array();
				for ($i = 0; $i < $cnt; $i++) {
					if ( empty($user->job_mail_date) || ($jobListOrg[$i]->open_date > $user->job_mail_date) ) {
						$jobList[] = $jobListOrg[$i];
					}
				}

				if (!empty($jobList[0])) {
					Mail::send(new JobToUser($user ,$jobList));
					User::where('id', $user->id)->update(['job_mail_date' => date("Y-m-d H:i:s")]);
				}
			}

			if ($user->favorite_mail_flag == '1') {
				
				if (!empty($user->favorite_job)) {
					$favorite = explode(",", $user->favorite_job);
	
					if (empty($user->favorite_mail_date)) {
						$jobList = Job::whereIn('id' ,$favorite)
							->where('open_flag' ,'1')
							->get();
						
					} else {
						$jobList = Job::whereIn('id' ,$favorite)
							->where('updated_at' , '>', $user->favorite_mail_date)
							->where('open_flag' ,'1')
							->get();
					}
					

					if (!empty($jobList[0])) {
						Mail::send(new FavoriteToUser($user ,$jobList));
						User::where('id', $user->id)->update(['favorite_mail_date' => date("Y-m-d H:i:s")]);
					}

				}
			}

		}
    }


}
