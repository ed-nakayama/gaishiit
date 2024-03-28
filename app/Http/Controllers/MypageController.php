<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log; 

use App\Models\Application;

use App\Models\Interview;
use App\Models\Job;
use App\Models\Event;
use App\Models\User;
use App\Models\SearchUserHist;
use App\Models\InterviewMessage;

class MypageController extends Controller
{
	public function __construct()
    {
        $this->middleware('auth:user');
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
		$loginUser = Auth::user();

		$searchUserHist = SearchUserHist::where('user_id' ,$loginUser->id)->first();

		$interviewList = Interview::join('users','interviews.user_id','=','users.id')
			->leftJoin('interview_msg_statuses', function ($join) use ($loginUser) {
                $join->on('interview_msg_statuses.interview_id','=','interviews.id')
					->where('interview_msg_statuses.reader_id' ,$loginUser->id);
			})
			->leftJoin('companies','interviews.company_id','=','companies.id')

			->addSelect(['last_update' => InterviewMessage::select('interview_messages.updated_at')
			    ->whereColumn('interview_messages.interview_id', 'interviews.id')
			    ->orderBy('interview_messages.updated_at', 'desc')
			    ->take(1)
			])

			->selectRaw('interviews.* ,interview_msg_statuses.read_flag, companies.name as company_name')
			->where('interviews.user_id' , $loginUser->id)
			->where('companies.open_flag' , '1')
//			->orderBy('interviews.updated_at' ,'desc')
			->orderBy('last_update' ,'desc')
			->limit(8)
			->get();

		$param = $searchUserHist->getParam();
		$JobCon = new JobController();
		$jobList = $JobCon->search_local($param);


		$favorite = explode(",", $loginUser->favorite_job);
		$favoriteList = Job::Join('companies','jobs.company_id','=','companies.id')
			->selectRaw('jobs.*  ,companies.name as company_name ,companies.logo_file as logo_file ,companies.image_file as image_file')
			->where('companies.open_flag' , '1')
			->where('jobs.open_flag' , '1')
			->whereNotNull('jobs.intro')
			->where('jobs.intro','!=','')
			->whereIn('jobs.id' ,$favorite)
			->orderBy('jobs.updated_at' ,'desc')
			->limit(4)
			->get();

		$eventList = Event::join('companies','events.company_id','=','companies.id')
			->selectRaw('events.* , companies.name as company_name , companies.logo_file as logo_file')
			->where('companies.open_flag' , '1')
			->where('events.open_flag' , '1')
			->where('events.event_date', '>=', date('Y-m-d'))
			->orderBy('event_date' , 'desc')
//			->limit($more_event)
			->limit(3)
			->get();

		return view('user.mypage' ,compact(
			'interviewList',
			'jobList',
			'favoriteList',
			'eventList',
			));
    }


}
