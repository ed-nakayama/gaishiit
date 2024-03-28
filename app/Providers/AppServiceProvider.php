<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Routing\UrlGenerator;
use Illuminate\Support\Facades\Auth;

use App\Models\Information;

use App\Models\ConstLocation;
use App\Models\ConstCat;
use App\Models\ConstMgrMember;
use App\Models\ConstMgrYear;
use App\Models\ConstPref;
use App\Models\ConstStage;
use App\Models\ConstStatus;
use App\Models\ConstResult;
use App\Models\ConstEnglish;
use App\Models\BusinessCat;
use App\Models\BusinessCatDetail;
use App\Models\JobCat;
use App\Models\JobCatDetail;
use App\Models\IndustoryCat;
use App\Models\IndustoryCatDetail;
use App\Models\CommitCat;
use App\Models\CommitCatDetail;
use App\Models\Company;
use App\Models\SearchUserHist;
use App\Models\Banner;
use App\Models\Income;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(UrlGenerator $url)
    {
        $url->forceScheme('https');


        // 管理画面用のクッキー名称、セッションテーブル名を変更する
        $uri = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '';
//        if (strpos($uri, '/admin/') === 0 || $uri === '/admin') {
        if (strpos($uri, '/admin/') === 0) {
            config([
                'session.cookie' => config('const.session_cookie_admin'),
                'session.table' => config('const.ssession_table_admin'),
            ]);
            
//        } elseif (strpos($uri, '/comp/') === 0 || $uri === '/comp') {
        } elseif (strpos($uri, '/comp/') === 0 ) {
            config([
                'session.cookie' => config('const.session_cookie_comp'),
                'session.table' => config('const.ssession_table_comp'),
            ]);
        }


    	$years = strftime("%Y");
		$fiscalYearList = array();
		for ($i = $years; $i >= 2021; $i--) {
			$fiscalYearList[$i] = $i;
		}
        view()->share('fiscalYearList' ,$fiscalYearList);


    	$years = strftime("%Y") + 5;
		$startYearList = array();
		for ($i = 2022; $i <= $years; $i++) {
			$startYearList[$i] = $i;
		}
        view()->share('startYearList' ,$startYearList);

    	$years = strftime("%Y") + 1;
		$yearList = array();
		for ($i = 1962; $i <= $years; $i++) {
			$yearList[$i] = $i;
		}
        view()->share('yearList' ,$yearList);

		$monthList = array(); // 未設定
		for ($i = 1; $i <= 12; $i++) {
			$monthList[$i] = $i;
		}
        view()->share('monthList' ,$monthList);

		$dayList = array(); // 未設定
		for ($i = 1; $i <= 31; $i++) {
			$dayList[$i] = $i;
		}
        view()->share('dayList' ,$dayList);

		$hourList = array(); // 未設定
		for ($i = 6; $i <= 22; $i++) {
			$hourList[$i] = $i;
		}
        view()->share('hourList' ,$hourList);

    	$years = strftime("%Y");
		$joinYearList = array();
		for ($i = 1970; $i <= $years; $i++) {
			$joinYearList[$i] = $i;
		}
        view()->share('joinYearList' ,$joinYearList);

    	$years = strftime("%Y");
		$leaveYearList = array();
		for ($i = 2013; $i <= $years; $i++) {
			$leaveYearList[$i] = $i;
		}
        view()->share('leaveYearList' ,$leaveYearList);

		$minList = array(); // 未設定
		$minList[0] = '00';
		$minList[1] = '15';
		$minList[2] = '30';
		$minList[3] = '45';
        view()->share('minList' ,$minList);

		view()->share('information', Information::where('open_flag', '1')->orderBy('id', 'desc')->get());

		view()->share('constStage', ConstStage::orderBy('id')->where('del_flag','0')->get());
		view()->share('constStatus', ConstStatus::orderBy('id')->where('del_flag','0')->get());
		view()->share('constLocation', ConstLocation::orderBy('id')->where('del_flag','0')->get());
		view()->share('constResult', ConstResult::orderBy('id')->where('del_flag','0')->get());
		view()->share('constEnglish', ConstEnglish::orderBy('id')->where('del_flag','0')->get());
		view()->share('constPref', ConstPref::orderBy('id')->where('del_flag','0')->get());
		view()->share('businessCat', BusinessCat::where('del_flag','0')->orderBy('order_num')->orderBy('id')->get());
		view()->share('businessCatDetail', BusinessCatDetail::orderBy('order_num')->orderBy('id')->where('del_flag','0')->get());
		view()->share('jobCat', JobCat::where('del_flag','0')->orderBy('order_num')->orderBy('id')->get());
		view()->share('jobCatDetail', JobCatDetail::where('del_flag','0')->orderBy('order_num')->orderBy('id')->get());
		view()->share('incomeList', Income::where('del_flag','0')->orderBy('id')->get());
		view()->share('industoryCat', IndustoryCat::where('del_flag','0')->orderBy('order_num')->orderBy('id')->get());
		view()->share('industoryCatDetail', IndustoryCatDetail::where('del_flag','0')->orderBy('order_num')->orderBy('id')->get());
		view()->share('commitCat', CommitCat::where('del_flag','0')->orderBy('order_num')->orderBy('id')->get());
		view()->share('commitCatDetail', CommitCatDetail::where('del_flag','0')->orderBy('order_num')->orderBy('id')->get());

 		view()->share('comp_A', Company::where('name_english','like', 'A%')->where('companies.open_flag' , '1')->get());
		view()->share('comp_B', Company::where('name_english','like', 'B%')->where('companies.open_flag' , '1')->get());
		view()->share('comp_C', Company::where('name_english','like', 'C%')->where('companies.open_flag' , '1')->get());
		view()->share('comp_D', Company::where('name_english','like', 'D%')->where('companies.open_flag' , '1')->get());
		view()->share('comp_E', Company::where('name_english','like', 'E%')->where('companies.open_flag' , '1')->get());
		view()->share('comp_F', Company::where('name_english','like', 'F%')->where('companies.open_flag' , '1')->get());
		view()->share('comp_G', Company::where('name_english','like', 'G%')->where('companies.open_flag' , '1')->get());
		view()->share('comp_H', Company::where('name_english','like', 'H%')->where('companies.open_flag' , '1')->get());
		view()->share('comp_I', Company::where('name_english','like', 'I%')->where('companies.open_flag' , '1')->get());
		view()->share('comp_J', Company::where('name_english','like', 'J%')->where('companies.open_flag' , '1')->get());
		view()->share('comp_K', Company::where('name_english','like', 'K%')->where('companies.open_flag' , '1')->get());
		view()->share('comp_L', Company::where('name_english','like', 'L%')->where('companies.open_flag' , '1')->get());
		view()->share('comp_M', Company::where('name_english','like', 'M%')->where('companies.open_flag' , '1')->get());
		view()->share('comp_N', Company::where('name_english','like', 'N%')->where('companies.open_flag' , '1')->get());
		view()->share('comp_O', Company::where('name_english','like', 'O%')->where('companies.open_flag' , '1')->get());
		view()->share('comp_P', Company::where('name_english','like', 'P%')->where('companies.open_flag' , '1')->get());
		view()->share('comp_Q', Company::where('name_english','like', 'Q%')->where('companies.open_flag' , '1')->get());
		view()->share('comp_R', Company::where('name_english','like', 'R%')->where('companies.open_flag' , '1')->get());
		view()->share('comp_S', Company::where('name_english','like', 'S%')->where('companies.open_flag' , '1')->get());
		view()->share('comp_T', Company::where('name_english','like', 'T%')->where('companies.open_flag' , '1')->get());
		view()->share('comp_U', Company::where('name_english','like', 'U%')->where('companies.open_flag' , '1')->get());
		view()->share('comp_V', Company::where('name_english','like', 'V%')->where('companies.open_flag' , '1')->get());
		view()->share('comp_W', Company::where('name_english','like', 'W%')->where('companies.open_flag' , '1')->get());
		view()->share('comp_X', Company::where('name_english','like', 'X%')->where('companies.open_flag' , '1')->get());
		view()->share('comp_Y', Company::where('name_english','like', 'Y%')->where('companies.open_flag' , '1')->get());
		view()->share('comp_Z', Company::where('name_english','like', 'Z%')->where('companies.open_flag' , '1')->get());


        if (strpos($uri, '/comp/') === 0) {
			view()->composer('*', function($view) {
				if(Auth::check()) {
					if (Auth::guard('comp')->check()) {
			 			$comp_member = app()->make('App\Http\Controllers\Comp\CompMemberController');
						$member_act = $comp_member->member_activity();
						$view->with('member_act', $member_act);
					}
				}
			});

		} else if (strpos($uri, '/admin/') === 0) {

			view()->share('comp_XA', Company::where('name_english','like', 'A%')->get());
			view()->share('comp_XB', Company::where('name_english','like', 'B%')->get());
			view()->share('comp_XC', Company::where('name_english','like', 'C%')->get());
			view()->share('comp_XD', Company::where('name_english','like', 'D%')->get());
			view()->share('comp_XE', Company::where('name_english','like', 'E%')->get());
			view()->share('comp_XF', Company::where('name_english','like', 'F%')->get());
			view()->share('comp_XG', Company::where('name_english','like', 'G%')->get());
			view()->share('comp_XH', Company::where('name_english','like', 'H%')->get());
			view()->share('comp_XI', Company::where('name_english','like', 'I%')->get());
			view()->share('comp_XJ', Company::where('name_english','like', 'J%')->get());
			view()->share('comp_XK', Company::where('name_english','like', 'K%')->get());
			view()->share('comp_XL', Company::where('name_english','like', 'L%')->get());
			view()->share('comp_XM', Company::where('name_english','like', 'M%')->get());
			view()->share('comp_XN', Company::where('name_english','like', 'N%')->get());
			view()->share('comp_XO', Company::where('name_english','like', 'O%')->get());
			view()->share('comp_XP', Company::where('name_english','like', 'P%')->get());
			view()->share('comp_XQ', Company::where('name_english','like', 'Q%')->get());
			view()->share('comp_XR', Company::where('name_english','like', 'R%')->get());
			view()->share('comp_XS', Company::where('name_english','like', 'S%')->get());
			view()->share('comp_XT', Company::where('name_english','like', 'T%')->get());
			view()->share('comp_XU', Company::where('name_english','like', 'U%')->get());
			view()->share('comp_XV', Company::where('name_english','like', 'V%')->get());
			view()->share('comp_XW', Company::where('name_english','like', 'W%')->get());
			view()->share('comp_XX', Company::where('name_english','like', 'X%')->get());
			view()->share('comp_XY', Company::where('name_english','like', 'Y%')->get());
			view()->share('comp_XZ', Company::where('name_english','like', 'Z%')->get());


		} else {
			view()->composer('*', function($view) {
				if (Auth::guard('user')->check()) {
		 			$user = app()->make('App\Http\Controllers\UserController');
					$user_act = $user->user_activity();
					$view->with('user_act', $user_act);

		 			$job = app()->make('App\Http\Controllers\JobController');
					$job_act = $job->job_activity();
					$view->with('job_act', $job_act);
				}
			});
		}

	}



}
