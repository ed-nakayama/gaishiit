<?php

use Illuminate\Http\Request;

use App\Http\Controllers\Api\CompanyApiController;
use App\Http\Controllers\Api\RpaApiController;
use App\Http\Controllers\JobRecommendController;
use App\Http\Middleware\VerifySalesforceApiToken;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/companies/lookup-id', [CompanyApiController::class, 'lookupId']); // ?salesforce_id=xxx

// SyncCompanyOpenStatus 用（id -> open_flag の参照専用）
Route::get('/companies/open-flag', [CompanyApiController::class, 'openFlag']);

// rpas テーブル（1件のみ）のデータ取得用
Route::get('/rpas', [RpaApiController::class, 'show']);

Route::middleware(VerifySalesforceApiToken::class)
    ->post('/job-recommend', [JobRecommendController::class, 'recommend']);

