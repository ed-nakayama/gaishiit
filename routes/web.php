<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/welcome', function () {
    return view('welcome');
});


Auth::routes([
    'register' => false,
    'reset'    => false,
    'verify'   => false
]);


Route::get('/', 'User\Auth\LoginController@top')->name('users.index');

Route::get('/comps', 'Comp\Auth\LoginController@showLoginForm')->name('comps.index');
Route::post('/comps', 'Comp\Auth\LoginController@login');

Route::get('/admins', 'Admin\Auth\LoginController@showLoginForm')->name('admins.index');
Route::post('/admins', 'Admin\Auth\LoginController@login');


/******************************************
* ¥æ¡¼¥¶¡¼
*******************************************/
Route::name('user.')->group(function () {

    require __DIR__.'/user.php';

});


/******************************************
* ´ë¶È
*******************************************/
Route::namespace('Comp')->prefix('comp')->name('comp.')->group(function () {

    require __DIR__.'/comp.php';

});

/******************************************
* ±¿±Ä´ÉÍý¼Ô
*******************************************/
Route::namespace('Admin')->prefix('admin')->name('admin.')->group(function(){

    require __DIR__.'/admin.php';

});


