<?php

/******************************************
* 企業
*******************************************/
// ログイン認証関連
Auth::routes([
    'register' => false,
    'reset'    => true,
    'verify'   => false
]);


// ログイン認証後
Route::middleware('auth:comp')->group(function () {

    // TOPページ
    Route::resource('mypage', 'CompMypageController', ['only' => 'index']);
});


	// ログインテスト
Route::get ('virtual/login', 'Auth\VirtualLoginController@showLoginForm2');

// パスワード忘れ
Route::post('password/reset',         'ResetPasswordController@reset')->name('password.reupdate');
Route::get ('password/reset',         'ForgotPasswordController@showLinkRequestForm')->name('password.request');
Route::post('password/email',         'ForgotPasswordController@sendResetLinkEmail')->name('password.email');
Route::get ('password/reset/{token}', 'ResetPasswordController@showResetForm')->name('password.reset');
Route::get ('password/complete',      'ResetCompleteController@index')->name('password.complete');


	// マイページ
//Route::get ('/mypage',      'CompMypageController@index')->name('mypage');
Route::get ('/mypage',        'CompMypageController@index_main');
Route::get ('/mypage/search', 'CompMypageController@search');
Route::post('/mypage/search', 'CompMypageController@search');
	
// マイページ改
Route::get ('/mypage/main',      'CompMypageController@index_main')->name('mypage.main');
Route::get ('/mypage/main/list', 'CompMypageController@list_main');
Route::post('/mypage/main/list', 'CompMypageController@list_main');

Route::get ('/mypage/newuser',      'CompMypageController@index_newuser')->name('mypage.newuser');
Route::get ('/mypage/newuser/list', 'CompMypageController@list_newuser');
Route::post('/mypage/newuser/list', 'CompMypageController@list_newuser');

Route::get ('/mypage/progress',      'CompMypageController@index_progress')->name('mypage.progress');
Route::get ('/mypage/progress/list', 'CompMypageController@list_progress');
Route::post('/mypage/progress/list', 'CompMypageController@list_progress');


// パスワード変更
Route::get ('/password/edit', 'CompMemberController@editPassword')->name('password.edit');
Route::post('/password/',     'CompMemberController@updatePassword')->name('password.update');
	
//	Route::get ('/user/list', 'CompUserController@list')->name('user.list');
//	Route::get ('/user/edit', 'CompUserController@edit')->name('user.edit');
//	Route::get ('/download',  'CompDownloadController@download');

// 企業関連
Route::get ('/edit',    'CompCompanyController@edit')->name('edit');
Route::post('/store',   'CompCompanyController@store')->name('store');
Route::get ('/store',   'CompCompanyController@store');
Route::get ('/preview', 'CompCompanyController@preview');
Route::get ('/change',  'CompCompanyController@change')->name('change');
Route::post('/change',  'CompCompanyController@change');

// ジョブ関連
Route::get ('/job',      'CompJobController@index');
Route::get ('/job/list', 'CompJobController@list')->name('job.list');
Route::post('/job/list', 'CompJobController@list');

Route::get ('/job/register', 'CompJobController@getRegister')->name('job.register');
Route::post('/job/register', 'CompJobController@postRegister');

Route::get ('/job/edit',       'CompJobController@edit');
Route::post('/job/edit',       'CompJobController@edit')->name('job.edit');
Route::get ('/job/edit/store', 'CompJobController@store');
Route::post('/job/edit/store', 'CompJobController@store');

Route::get ('/job/change', 'CompJobController@edit')->name('job.change');
Route::post('/job/change', 'CompJobController@change');


// イベント関連
Route::get ('/event',      'CompEventController@index');
Route::get ('/event/list', 'CompEventController@list')->name('event.list');
Route::post('/event/list', 'CompEventController@list');

Route::get ('/event/register', 'CompEventController@getRegister')->name('event.register');
Route::post('/event/register', 'CompEventController@postRegister');

Route::get ('/event/detail', 'CompEventController@detail')->name('event.detail');
Route::post('/event/detail', 'CompEventController@detail');

Route::get ('/event/edit', 'CompEventController@edit')->name('event.edit');
Route::post('/event/edit', 'CompEventController@store');

Route::get ('/event/change', 'CompEventController@edit')->name('event.change');
Route::post('/event/change', 'CompEventController@change');

Route::get ('/event/aprove', 'CompEventController@aprove')->name('event.aprove');
Route::post('/event/aprove', 'CompEventController@aprove');

// 部署関連
Route::get ('/unit',      'CompUnitController@index');
Route::get ('/unit/list', 'CompUnitController@list')->name('unit.list');
Route::post('/unit/list', 'CompUnitController@list');

Route::get ('/unit/register', 'CompUnitController@getRegister')->name('unit.register');
Route::post('/unit/register', 'CompUnitController@postRegister');

Route::get ('/unit/edit',       'CompUnitController@edit');
Route::post('/unit/edit',       'CompUnitController@edit')->name('unit.edit');
Route::get ('/unit/edit/store', 'CompUnitController@store');
Route::post('/unit/edit/store', 'CompUnitController@store');

Route::get ('/unit/change', 'CompUnitController@edit')->name('unit.change');
Route::post('/unit/change', 'CompUnitController@change');

Route::get ('/admin/unit',      'CompUnitController@adminIndex');
Route::get ('/admin/unit/list', 'CompUnitController@adminList')->name('admin.unit.list');
Route::post('/admin/unit/list', 'CompUnitController@adminList');

Route::get ('/admin/unit/edit', 'CompUnitController@adminEdit')->name('admin.unit.edit');
Route::post('/admin/unit/edit', 'CompUnitController@store');

Route::get ('/admin/unit/register', 'CompUnitController@adminGetRegister')->name('admin.unit.register');
Route::post('/admin/unit/register', 'CompUnitController@adminPostRegister');

// ユーザ関連
Route::get ('/client',      'CompClientController@index');
Route::get ('/client/list', 'CompClientController@list')->name('client.list');
Route::post('/client/list', 'CompClientController@list');

Route::get ('/clientend',      'CompClientController@endIndex');
Route::get ('/clientend/list', 'CompClientController@endList')->name('clientend.list');
Route::post('/clientend/list', 'CompClientController@endList');

Route::get ('/client/enter',      'CompClientController@enter');
Route::get ('/client/enter/list', 'CompClientController@enterList');
Route::post('/client/enter/list', 'CompClientController@enterList')->name('client.enter.list');
Route::get ('/client/enter/save', 'CompClientController@enterSave')->name('cliententer.save');
Route::post('/client/enter/save', 'CompClientController@enterSave');

Route::get ('/user',          'CompUserController@index');
Route::get ('/user/list',     'CompUserController@list');
Route::post('/user/list',     'CompUserController@list');
Route::get ('/user/freelist', 'CompUserController@freeList');
Route::post('/user/freelist', 'CompUserController@freeList');

Route::get ('/user/detail', 'CompUserController@detail');
Route::post('/user/detail', 'CompUserController@detail');

Route::get ('/user/memo', 'CompUserController@memo');
Route::post('/user/memo', 'CompUserController@memo');

// PDF出力
Route::get ('/pdf/base', 'CompUserController@userBasePdf');
Route::post('/pdf/base', 'CompUserController@userBasePdf');
	
Route::get ('/pdf/cv', 'CompUserController@userCvPdf');
Route::post('/pdf/cv', 'CompUserController@userCvPdf');
	
Route::get ('/pdf/cv/eng', 'CompUserController@userCvEngPdf');
Route::post('/pdf/cv/eng', 'CompUserController@userCvEngPdf');
	
Route::get ('/pdf/vitae', 'CompUserController@userVitaePdf');
Route::post('/pdf/vitae', 'CompUserController@userVitaePdf');

Route::get ('/candidate',      'CompUserController@canIndex');
Route::get ('/candidate/list', 'CompUserController@canList');
Route::post('/candidate/list', 'CompUserController@canList');

Route::get ('/candidate/freelist', 'CompUserController@canFreeList');
Route::post('/candidate/reelist',  'CompUserController@canFreeList');

// 企業メンバー関連
Route::get ('/member',     'CompMemberController@index');
Route::get ('/member/add', 'CompMemberController@add');
Route::post('/member/add', 'CompMemberController@add');

Route::get ('/member/more', 'CompMemberController@more');
Route::post('/member/more', 'CompMemberController@more');

Route::get ('/member/list', 'CompMemberController@list');
Route::post('/member/list', 'CompMemberController@list');

// 個人設定
Route::get ('/member/setting',       'CompMemberController@setting');
Route::get ('/member/setting/store', 'CompMemberController@settingStore');
Route::post('/member/setting/store', 'CompMemberController@settingStore');

// メッセージ関連
Route::get ('/msg/casual/list', 'CompInterviewController@casualList');
Route::post('/msg/casual/list', 'CompInterviewController@casualList');
Route::get ('/msg/formal/list', 'CompInterviewController@formalList');
Route::post('/msg/formal/list', 'CompInterviewController@formalList');
Route::get ('/msg/event/list',  'CompInterviewController@eventList');
Route::post('/msg/event/list',  'CompInterviewController@eventList');

Route::get ('/interview/flow',     'CompInterviewController@interviewFlow');
Route::post('/interview/flow',     'CompInterviewController@interviewFlow');
Route::get ('/interview/flowpost', 'CompInterviewController@interviewFlowPost');
Route::post('/interview/flowpost', 'CompInterviewController@interviewFlowPost');
Route::get ('/interview/mask',     'CompInterviewController@interviewMask');
Route::post('/interview/mask',     'CompInterviewController@interviewMask');

Route::get ('/interview/request',       'CompInterviewController@interview_request');
Route::post('/interview/request',       'CompInterviewController@interview_request');
Route::get ('/interview/request/store', 'CompInterviewController@interview_request_store');
Route::post('/interview/request/store', 'CompInterviewController@interview_request_store');

Route::get ('/interview/aprove', 'CompInterviewController@aprove');
Route::post('/interview/aprove', 'CompInterviewController@aprove');

// 請求関連
Route::get ('/billing',      'CompInterviewController@billing');
Route::post('/billing',      'CompInterviewController@billing');
Route::get ('/billing/hist', 'CompInterviewController@billingHist');
Route::post('/billing/hist', 'CompInterviewController@billingHist');

// FAQ
Route::get ('/faq/list',   'CompFaqController@index');
Route::get ('/faq',        'CompFaqController@faq')->name('faq');
Route::post('/faq',        'CompFaqController@store');
Route::get ('/faq/change', 'CompFaqController@edit')->name('faq.change');
Route::post('/faq/change', 'CompFaqController@change');
	
Route::get ('/claim/every',   'CompBillController@claimEvery')->name('claim.every');
Route::get ('/claim/monthly', 'CompBillController@claimMonthly')->name('claim.monthly');

