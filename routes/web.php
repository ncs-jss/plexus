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

// Routes
Route::get('society/login', 'HomeController@society');
Route::get('login', 'HomeController@user');
Route::get('register', 'HomeController@userRegister');
Route::get('user/edit', 'User\UserController@profile');

Route::get('/', 'Event\EventController@index');

Route::get('society/dashboard', 'Society\SocietyController@dashboard');

Route::get('/event/{eventId}/leaderboard', 'HomeController@showLeaderboard');

Route::get('event/{id}/dashboard', 'Event\EventController@dashboard');

Route::resource('event', 'Event\EventController');
Route::resource('society', 'Society\SocietyController');
Route::resource('user', 'User\UserController');
Route::resource('event/{eventId}/question', 'Event\QuestionController');

Route::group(
    ['prefix' => 'api'], function() {

    Route::get('user/edit', 'User\UserControllerApi@profile');
    Route::post('society/login', 'Society\SocietyControllerApi@login');
    Route::post('user/login', 'User\UserControllerApi@login');
    Route::get('/event/{eventId}/user', 'User\UserControllerApi@userInfoEvent');

    Route::get('event/{id}/dashboard', 'Event\EventControllerApi@dashboard');

    Route::get('society/dashboard', 'Society\SocietyControllerApi@dashboard');
    Route::get('/event/{eventId}/leaderboard', 'HomeController@leaderboard');
    Route::get('user/login', 'HomeController@user');
    Route::resource('event/{eventId}/question', 'Event\QuestionControllerApi');
    Route::post('event/{eventId}/answer/{id}', 'Event\AnswerControllerApi@store');
    Route::resource('society', 'Society\SocietyControllerApi');
    Route::resource('user', 'User\UserControllerApi');
    Route::resource('event', 'Event\EventControllerApi');

});


Route::get('/logout', 'HomeController@logout');