<?php

/******************************************
* 運営管理者
*******************************************/
// ログイン認証関連
Auth::routes([
    'register' => false,
    'reset'    => true,
    'verify'   => false
]);

// ログイン認証後
Route::middleware('auth:admin')->group(function () {

	// TOPページ
	Route::resource('mypage', 'MypageController', ['only' => 'index']);
});

// マイページ
// Route::resource('mypage', 'MypageController', ['only' => 'index']);

// パスワード忘れ
Route::post('password/reset', 'ResetPasswordController@reset')->name('password.reupdate');
Route::get ('password/reset', 'ForgotPasswordController@showLinkRequestForm')->name('password.request');

Route::post('password/email',         'ForgotPasswordController@sendResetLinkEmail')->name('password.email');
Route::get ('password/reset/{token}', 'ResetPasswordController@showResetForm')->name('password.reset');
Route::get ('password/complete',      'ResetCompleteController@index')->name('password.complete');


// マイページ
Route::get ('/mypage',      'MypageController@index')->name('mypage');
Route::get ('/mypage/list', 'MypageController@list')->name('mypage.list');
Route::post('/mypage/list', 'MypageController@list')->name('mypage.list');

Route::get ('/mypage/progress',       'MypageController@progress')->name('mypage.progress');
Route::get ('/mypage/progress/list',  'MypageController@progress_list')->name('mypage.progress.list');
Route::post('/mypage/progress/list',  'MypageController@progress_list');

Route::get('/mypage/enter',       'MypageController@mypage_enter')->name('mypage.enter');
Route::get('/mypage/enter/list',  'MypageController@enter_list')->name('mypage.enter.list');
Route::post('/mypage/enter/list', 'MypageController@enter_list');

Route::get ('/mypage/joblist',          'MypageController@joblist')->name('mypage.joblist');
Route::get ('/mypage/joblist/list',     'MypageController@joblist_list');
Route::post('/mypage/joblist/list',     'MypageController@joblist_list');
Route::get ('/mypage/job/edit',         'MypageController@job_edit')->name('mypage.job.edit');
Route::post('/mypage/job/edit',         'MypageController@job_edit');
Route::get ('/mypage/job/change',       'MypageController@job_change');
Route::post('/mypage/job/change',       'MypageController@job_change');
Route::get ('/mypage/job/post',         'MypageController@job_post');
Route::post('/mypage/job/post',         'MypageController@job_post');
Route::get ('/mypage/joblist/download', 'MypageController@joblist_download');

Route::get ('/mypage/joblist/upload', 'MypageController@upload_csv');
Route::post('/mypage/joblist/upload', 'MypageController@upload_csv');

Route::get ('/mypage/jobsfc',             'MypageController@jobsfc');
Route::post('/mypage/jobsfc',             'MypageController@jobsfc');
//	Route::get('/mypage/jobsfc/download', 'MypageController@joblist_download');

Route::get ('/mypage/eval',       'MypageController@eval')->name('mypage.eval');
Route::post('/mypage/eval',       'MypageController@eval');
Route::get ('/mypage/eval/edit',  'MypageController@eval_edit')->name('mypage.eval.edit');
Route::post('/mypage/eval/edit',  'MypageController@eval_edit');
Route::get ('/mypage/eval/store', 'MypageController@eval_store')->name('mypage.eval.store');
Route::post('/mypage/eval/store', 'MypageController@eval_store');

// パスワード変更
Route::get ('/password/edit', 'AdminController@editPassword')->name('password.edit');
Route::post('/password/',     'AdminController@updatePassword')->name('password.update');

// お知らせ関連
Route::get ('/info/list',   'AdminInfoController@index');
Route::get ('/info',        'AdminInfoController@info')->name('info');
Route::post('/info',        'AdminInfoController@store');
Route::get ('/info/change', 'AdminInfoController@change')->name('info.change');
Route::post('/info/change', 'AdminInfoController@change');


// 運営担当者関連
Route::get ('/admin/list',     'AdminController@index');
Route::get ('/admin/register', 'AdminController@getRegister');
Route::post('/admin/register', 'AdminController@postRegister');

Route::get ('/admin/edit', 'AdminController@edit');
Route::post('/admin/edit', 'AdminController@edit');

Route::get ('/update/token', 'ApiController@updateToken');
Route::post('/update/token', 'ApiController@updateToken');

// ユーザ関連
Route::get ('/user/list',   'AdminUserController@index');
Route::get ('/user/aprove', 'AdminUserController@aprove');
Route::post('/user/aprove', 'AdminUserController@aprove');

Route::get ('/user/hist',     'AdminUserController@aproveHist');
Route::post('/user/hist',     'AdminUserController@aproveHist');
Route::get ('/user/histback', 'AdminUserController@histBack');
Route::post('/user/histback', 'AdminUserController@histBack');
	
Route::get ('/user/detail', 'AdminUserController@detail');
Route::post('/user/detail', 'AdminUserController@detail')->name('user.detail');

Route::get ('/user/change', 'AdminUserController@change');
Route::post('/user/change', 'AdminUserController@change');

Route::get ('/user/changehist', 'AdminUserController@changeHist');
Route::post('/user/changehist', 'AdminUserController@changeHist');

Route::get ('/user/edit',  'AdminUserController@edit')->name('user.edit');
Route::post('/user/store', 'AdminUserController@store')->name('user.store');
Route::get ('/user/store', 'AdminUserController@store');

    // 企業関連
Route::get ('/comp/list', 'AdminCompanyController@list')->name('comp.list');
Route::post('/comp/list', 'AdminCompanyController@list');

Route::get ('/comp/register', 'AdminCompanyController@getRegister');
Route::post('/comp/register', 'AdminCompanyController@postRegister');

Route::get ('/comp/edit',   'AdminCompanyController@edit');
Route::post('/comp/edit',   'AdminCompanyController@edit')->name('comp.edit');
Route::get ('/comp/inmail', 'AdminCompanyController@inMailPost');
Route::post('/comp/inmail', 'AdminCompanyController@inMailPost');

// 企業メンバー関連
Route::get ('/member/list', 'AdminMemberController@list');
Route::post('/member/list', 'AdminMemberController@list')->name('member.list');

Route::get ('/member/add', 'AdminMemberController@add');
Route::post('/member/add', 'AdminMemberController@add');

Route::get ('/member/store', 'AdminMemberController@store');
Route::post('/member/store', 'AdminMemberController@store');

    // 候補者関連
Route::get ('/candidate',      'AdminUserController@canIndex');
Route::get ('/candidate/list', 'AdminUserController@canList');
Route::post('/candidate/list', 'AdminUserController@canList');

// オーナーシップ
Route::get ('/ownership', 'AdminUserController@ownership');
Route::post('/ownership', 'AdminUserController@ownership');

// ログ関連
Route::get ('/log/list', 'LoggingController@list')->name('log.list');
Route::post('/log/list', 'LoggingController@list');

// 請求関連
Route::get ('/bill', 'AdminBillController@index');
Route::post('/bill', 'AdminBillController@list');

Route::get ('/bill/detail', 'AdminBillController@detail');
Route::post('/bill/detail', 'AdminBillController@detail')->name('bill.detail');

Route::get ('/bill/change', 'AdminBillController@change');
Route::post('/bill/change', 'AdminBillController@change');

Route::get ('/nobill', 'AdminBillController@no_bill');
Route::post('/nobill', 'AdminBillController@no_bill');

Route::get ('/claim/every',   'AdminBillController@claimEvery')->name('claim.every');
Route::get ('/claim/monthly', 'AdminBillController@claimMonthly')->name('claim.monthly');

Route::get ('/claim/every/store', 'AdminBillController@claimEveryStore');
Route::post('/claim/every/store', 'AdminBillController@claimEveryStore');

Route::get ('/claim/monthly/store', 'AdminBillController@claimMonthlyStore');
Route::post('/claim/monthly/store', 'AdminBillController@claimMonthlyStore');

// 業種
Route::get ('/buscat',       'AdminCatController@buscatIndex');
Route::get ('/buscat/add',   'AdminCatController@buscatAdd');
Route::post('/buscat/add',   'AdminCatController@buscatAdd');
Route::get ('/buscat/store', 'AdminCatController@buscatStore');
Route::post('/buscat/store', 'AdminCatController@buscatStore');

// 業種詳細
Route::get ('/buscatdetail',       'AdminCatController@buscatDetailList');
Route::post('/buscatdetail',       'AdminCatController@buscatDetailList')->name('buscat_detail.list');
Route::get ('/buscatdetail/add',   'AdminCatController@buscatDetailAdd');
Route::post('/buscatdetail/add',   'AdminCatController@buscatDetailAdd');
Route::get ('/buscatdetail/store', 'AdminCatController@buscatDetailStore');
Route::post('/buscatdetail/store', 'AdminCatController@buscatDetailStore');

// 職種
Route::get ('/jobcat',       'AdminCatController@jobcatIndex');
Route::get ('/jobcat/add',   'AdminCatController@jobcatAdd');
Route::post('/jobcat/add',   'AdminCatController@jobcatAdd');
Route::get ('/jobcat/store', 'AdminCatController@jobcatStore');
Route::post('/jobcat/store', 'AdminCatController@jobcatStore');

// 職種詳細
Route::get ('/jobcatdetail',       'AdminCatController@jobcatDetailList');
Route::post('/jobcatdetail',       'AdminCatController@jobcatDetailList')->name('jobcat_detail.list');
Route::get ('/jobcatdetail/add',   'AdminCatController@jobcatDetailAdd');
Route::post('/jobcatdetail/add',   'AdminCatController@jobcatDetailAdd');
Route::get ('/jobcatdetail/store', 'AdminCatController@jobcatDetailStore');
Route::post('/jobcatdetail/store', 'AdminCatController@jobcatDetailStore');

// インダストリ
Route::get ('/industorycat',       'AdminCatController@industoryCatIndex');
Route::get ('/industorycat/add',   'AdminCatController@industoryCatAdd');
Route::post('/industorycat/add',   'AdminCatController@industoryCatAdd');
Route::get ('/industorycat/store', 'AdminCatController@industoryCatStore');
Route::post('/industorycat/store', 'AdminCatController@industoryCatStore');

// インダストリ詳細
Route::get ('/industorycatdetail',       'AdminCatController@industoryCatDetailList');
Route::post('/industorycatdetail',       'AdminCatController@industoryCatDetailList')->name('industorycat_detail.list');
Route::get ('/industorycatdetail/add',   'AdminCatController@industoryCatDetailAdd');
Route::post('/industorycatdetail/add',   'AdminCatController@industoryCatDetailAdd');
Route::get ('/industorycatdetail/store', 'AdminCatController@industoryCatDetailStore');
Route::post('/industorycatdetail/store', 'AdminCatController@industoryCatDetailStore');

// こだわり
Route::get ('/commitcat',       'AdminCatController@commitCatIndex');
Route::get ('/commitcat/add',   'AdminCatController@commitCatAdd');
Route::post('/commitcat/add',   'AdminCatController@commitCatAdd');
Route::get ('/commitcat/store', 'AdminCatController@commitCatStore');
Route::post('/commitcat/store', 'AdminCatController@commitCatStore');

// こだわり詳細
Route::get ('/commitcatdetail',       'AdminCatController@commitCatDetailList');
Route::post('/commitcatdetail',       'AdminCatController@commitCatDetailList')->name('commitcat_detail.list');
Route::get ('/commitcatdetail/add',   'AdminCatController@commitCatDetailAdd');
Route::post('/commitcatdetail/add',   'AdminCatController@commitCatDetailAdd');
Route::get ('/commitcatdetail/store', 'AdminCatController@commitCatDetailStore');
Route::post('/commitcatdetail/store', 'AdminCatController@commitCatDetailStore');

// FAQ
Route::get ('/faq/list',   'AdminFaqController@index');
Route::get ('/faq',        'AdminFaqController@faq')->name('faq');
Route::post('/faq',        'AdminFaqController@store');
Route::get ('/faq/change', 'AdminFaqController@edit')->name('faq.change');
Route::post('/faq/change', 'AdminFaqController@change');

Route::get ('/ask', 'AdminFaqController@ask');

// ピックアップ
Route::get ('/pickup', 'AdminPickupController@index')->name('pickup');
Route::post('/pickup', 'AdminPickupController@store');

// バナー
Route::get ('/banner', 'AdminBannerController@index')->name('banner');
Route::post('/banner', 'AdminBannerController@store');

Route::get ('/api/joblist', 'ApiController@joblist');

// ブログ
Route::get ('/blog/list',   'BlogController@index');
Route::get ('/blog',        'BlogController@detail')->name('blog.detail');
Route::post('/blog',        'BlogController@store');
Route::get ('/blog/change', 'BlogController@edit')->name('blog.change');
Route::post('/blog/change', 'BlogController@change');

