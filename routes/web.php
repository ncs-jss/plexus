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

Route::get('/', 'Event\EventController@index');
Route::group(
    ['prefix' => 'api'], function() {

    Route::get('society/login', 'HomeController@society');
    Route::post('society/login', 'Society\SocietyController@login');

    Route::get('user/login', 'HomeController@user');
    Route::post('user/login', 'User\UserController@login');

    Route::resource('society', 'Society\SocietyController');
    Route::resource('user', 'User\UserController');
    Route::resource('event', 'Event\EventController');

});

Route::get('password/reset', function() {
    return "helo";
});

Route::get('/logout', 'HomeController@logout');


//
