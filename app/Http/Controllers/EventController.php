<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log; 
use Illuminate\Validation\Rule;

use App\Http\Controllers\Controller;

use App\Models\Company;
use App\Models\Event;
use App\Models\EventPr;
use App\Models\Ranking;
use App\Models\Income;
use App\Models\Evaluation;

class EventController extends Controller
{
	public function __construct()
	{
//		$this->middleware('auth:user');
	}


/*************************************
* 初期表示
**************************************/
	public function index()
	{
		$eventList = Event::Join('companies' , 'events.company_id', 'companies.id')
			->selectRaw('events.*, ' . 
			  'companies.name as company_name ,companies.logo_file as logo_file ,companies.image_file as image_file')
			->where('companies.open_flag' ,'1')
			->where('events.open_flag' , '1')
			->where('events.event_date', '>=', date('Y-m-d'))
			->paginate(10);

 		return view('user.event' ,compact(
 			'eventList',
 			));
	}


/*************************************
* イベント詳細
**************************************/
	public function detail(Request $request, $compId, $eventId)
	{
		$loginUser = Auth::guard('user')->user();

		$event = Event::find($eventId);

		if (empty($event)) {
 			return view('user.not_ready');
		}

		$eventPr = EventPr::select()->where('event_id' , $eventId)->get();

		$comp = Company::find($compId);

		$interview = null;
		if (!empty($loginUser)) {
			$my_count = Evaluation::where('company_id' ,$comp->id)
				->where('user_id' ,$loginUser->id)
				->count();
		} else {
			$my_count = 0;
		}
		
		// クチコミカテゴリ取得
		$cat['sel'] = null;

		$ranking = Ranking::find($comp->id);

 		return view('user.event_detail' ,compact(
 			'comp',
 			'event',
 			'cat',
			'ranking',
 			'my_count',
 			));
	}



}
