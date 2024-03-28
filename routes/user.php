<?php

/******************************************
* ユーザー
*******************************************/
// ログイン認証関連
Auth::routes([
    'register' => false,
    'reset'    => true,
    'verify'   => false
]);



// クチコミ
Route::get ('/chart', 'ChartController@index');

Route::get ('/eval/regist', 'EvalController@index')->name('eval.regist');
Route::post('/eval/regist', 'EvalController@index');

Route::get ('/eval/store', 'EvalController@store')->name('eval.store');
Route::post('/eval/store', 'EvalController@store');

Route::get ('/eval/confirm', 'EvalController@confirm')->name('eval.confirm');
Route::post('/eval/confirm', 'EvalController@confirm');

Route::get ('/eval/send', 'EvalController@send')->name('eval.send');
Route::post('/eval/send', 'EvalController@send');

Route::get ('/eval/complete', 'EvalController@complete')->name('eval.complete');


// プライバシーポリシー
Route::get('/corporate', function () { 
    return view('user.corporate');
});
Route::get('/privacy', function () { 
    return view('user.privacy');
});
Route::get('/kiyaku', function () { 
    return view('user.kiyaku');
});


// ユーザ新規登録確認
Route::get ('/register/confirm', 'User\Auth\RegisterController@confirm')->name('register.confirm');
Route::post('/register/confirm', 'User\Auth\RegisterController@confirm');

// ユーザ新規登録完了
Route::get('/register/complete', function () { 
    return view('user.user_regist_complete');
});

// ユーザ新規登録
Route::get ('/register', 'User\Auth\RegisterController@getRegister')->name('register');
Route::post('/register', 'User\Auth\RegisterController@postRegister');

// パスワード忘れ
Route::post('/password/reset',         'User\ResetPasswordController@reset')->name('password.reupdate');
Route::get ('/password/reset',         'User\ForgotPasswordController@showLinkRequestForm')->name('password.request');
Route::post('/password/email',         'User\ForgotPasswordController@sendResetLinkEmail')->name('password.email');
Route::get ('/password/reset/{token}', 'User\ResetPasswordController@showResetForm')->name('password.reset');
Route::get ('/password/complete',      'User\ResetCompleteController@index')->name('password.complete');

Route::get ('/api/complist', 'ApiController@complist');
Route::post('/api/complist', 'ApiController@complist');

// マイページ
Route::get ('/mypage', 'MypageController@index')->name('mypage');

// 個人設定
Route::get ('/setting',       'UserController@setting');
Route::get ('/setting/store', 'UserController@settingStore');
Route::post('/setting/store', 'UserController@settingStore');

// メールアドレス確認メールを送信
Route::post('/email/change',  'ChangeEmailController@sendChangeEmailLink');
Route::get ('/reset/{token}', 'ChangeEmailController@reset');

// パスワード変更
Route::get ('/password/edit', 'UserController@editPassword')->name('password.edit');
Route::post('/password/',     'UserController@updatePassword')->name('password.update');

// 基本情報
Route::get ('/base',       'UserController@base');
Route::post('/base',       'UserController@base');
Route::get ('/option',     'UserController@option');
Route::post('/option',     'UserController@option');
Route::get ('/base/store', 'UserController@base_store');
Route::post('/base/store', 'UserController@base_store');

// 職務経歴書
Route::get ('/cv',        'UserController@cv');
Route::post('/cv',        'UserController@cv');
Route::get ('/cv/store1', 'UserController@cv_store1');
Route::post('/cv/store1', 'UserController@cv_store1');
Route::get ('/cv/store2', 'UserController@cv_store2');
Route::post('/cv/store2', 'UserController@cv_store2');
Route::get ('/cv/store3', 'UserController@cv_store3');
Route::post('/cv/store3', 'UserController@cv_store3');

// 職務経歴書（英語）
Route::get ('/cv/eng',        'UserController@cv_eng');
Route::post('/cv/eng',        'UserController@cv_eng');
Route::get ('/cv/eng/store1', 'UserController@cv_eng_store1');
Route::post('/cv/eng/store1', 'UserController@cv_eng_store1');
Route::get ('/cv/eng/store2', 'UserController@cv_eng_store2');
Route::post('/cv/eng/store2', 'UserController@cv_eng_store2');
Route::get ('/cv/eng/store3', 'UserController@cv_eng_store3');
Route::post('/cv/eng/store3', 'UserController@cv_eng_store3');

// 履歴書
Route::get ('/vitae',       'UserController@vitae');
Route::post('/vitae',       'UserController@vitae');
Route::get ('/vitae/store', 'UserController@vitae_store');
Route::post('/vitae/store', 'UserController@vitae_store');

// 企業を探す
Route::get ('/company', 'CompanyController@list')->name('comp.list');
Route::post('/company', 'CompanyController@list');

// 企業ランキング
Route::get ('/company/ranking', 'CompanyController@ranking')->name('comp.ranking');

// 企業詳細
Route::get ('/company/{compId}', 'CompanyController@comp_detail')->name('comp.detail');
Route::post('/company/{compId}', 'CompanyController@comp_detail');

// 企業求人一覧
Route::get ('/company/{compId}/joblist', 'JobController@comp_job_list')->name('comp.job_list');
Route::post('/company/{compId}/joblist', 'JobController@comp_job_list');

Route::get ('/company/{compId}/eval/{evalCat}', 'CompanyController@comp_detail');
Route::post('/company/{compId}/eval/{evalCat}', 'CompanyController@comp_detail');

// クチコミ 給与
Route::get ('/company/{compId}/salary', 'CompanyController@eval_category');
Route::post('/company/{compId}/salary', 'CompanyController@eval_category');

// クチコミ 福利厚生
Route::get ('/company/{compId}/welfare', 'CompanyController@eval_category');
Route::post('/company/{compId}/welfare', 'CompanyController@eval_category');

// クチコミ 育成
Route::get ('/company/{compId}/upbring', 'CompanyController@eval_category');
Route::post('/company/{compId}/upbring', 'CompanyController@eval_category');

// クチコミ 法令遵守の意識
Route::get ('/company/{compId}/compliance', 'CompanyController@eval_category');
Route::post('/company/{compId}/compliance', 'CompanyController@eval_category');

// クチコミ 社員のモチベーション
Route::get ('/company/{compId}/motivation', 'CompanyController@eval_category');
Route::post('/company/{compId}/motivation', 'CompanyController@eval_category');

// クチコミ ワークライフバランス
Route::get ('/company/{compId}/worklife', 'CompanyController@eval_category');
Route::post('/company/{compId}/worklife', 'CompanyController@eval_category');

// クチコミ リモート勤務
Route::get ('/company/{compId}/remote', 'CompanyController@eval_category');
Route::post('/company/{compId}/remote', 'CompanyController@eval_category');

// クチコミ 定年
Route::get ('/company/{compId}/retirement', 'CompanyController@eval_category');
Route::post('/company/{compId}/retirement', 'CompanyController@eval_category');

// 部署詳細
Route::get ('/company/{compId}/unit/{unitId}', 'CompanyController@unit_detail')->name('comp.unit.detail');
Route::post('/company/{compId}/unit/{unitId}', 'CompanyController@unit_detail');

// イベント
Route::get ('/event',        'EventController@index')->name('event');
Route::get ('/company/{compId}/event/{eventId}', 'EventController@detail')->name('comp.event.detail');
Route::post('/company/{compId}/event/{eventId}', 'EventController@detail');


// ジョブ
Route::get ('/job', 'JobController@index')->name('comp.job');

Route::get ('/job/list', 'JobController@list')->name('job.job_list');
Route::post('/job/list', 'JobController@list');

Route::get ('/job/list/{param1?}/{param2?}/{param3?}', 'JobController@list');
Route::post ('/job/list/{param1?}/{param2?}/{param3?}', 'JobController@list');

// ジョブ詳細
Route::get ('/company/{compId}/{jobId}', 'JobController@detail')->name('comp.job.detail');
Route::post('/company/{compId}/{jobId}', 'JobController@detail');

Route::get ('/job/favorite', 'JobController@favorite');

Route::get ('/job/favorite/add', 'JobController@favoriteAdd');
Route::post('/job/favorite/add', 'JobController@favoriteAdd');

// メッセージ
Route::get ('/interview/flow',         'InterviewController@interviewFlow');
Route::post('/interview/flow',         'InterviewController@interviewFlow');
Route::get ('/interview/flowpost',     'InterviewController@interviewFlowPost');
Route::post('/interview/flowpost',     'InterviewController@interviewFlowPost');
Route::get ('/interview/request',      'InterviewController@interviewRequest');
Route::post('/interview/request',      'InterviewController@interviewRequest')->name('interview.request');
Route::get ('/interview/request/send', 'InterviewController@interviewRequestSend');
Route::post('/interview/request/send', 'InterviewController@interviewRequestSend');

Route::get ('/interview',        'InterviewController@index')->name('interview');
Route::get ('/interview/list',   'InterviewController@list');
Route::post('/interview/list',   'InterviewController@list');
Route::get ('/interview/detail', 'InterviewController@detail')->name('interview.detail');
Route::post('/interview/detail', 'InterviewController@detail');
Route::get ('/interview/store',  'InterviewController@store')->name('interview.store');
Route::post('/interview/store',  'InterviewController@store');

Route::get ('/interview/aprove', 'InterviewController@aprove');
Route::post('/interview/aprove', 'InterviewController@aprove');

// 転職エージェントに相談
Route::get ('/agent/request',      'InterviewController@agentRequest');
Route::post('/agent/request',      'InterviewController@agentRequest')->name('agent.request');
Route::get ('/agent/request/send', 'InterviewController@agentRequestSend');
Route::post('/agent/request/send', 'InterviewController@agentRequestSend');

// お問合せ 企業
Route::get ('/compfaq',       'FAQController@comp_faq');
Route::post('/compfaq',       'FAQController@comp_faq');
Route::post('/compfaq/store', 'FAQController@comp_faq_store');


// お問合せ 運営
Route::get ('/adminfaq',       'FAQController@admin_faq');
Route::post('/adminfaq',       'FAQController@admin_faq');
Route::post('/adminfaq/store', 'FAQController@admin_faq_store');


// ブログ
Route::get ('/blog',   'BlogController@index');

Route::get ('/blog/{cat}',   'BlogController@category');
Route::post('/blog/{cat}',   'BlogController@category');

Route::get ('/blog/{cat}/{detail}',   'BlogController@detail');
Route::post('/blog/{cat}/{detail}',   'BlogController@detail');


// ログイン認証後
Route::middleware('auth:user')->group(function () {

    // TOPページ
    Route::resource('mypage', 'MypageController', ['only' => 'index']);
});

