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

Route::get('/', 'Event\EventController@index');
Route::resource('event', 'Event\EventController');
Route::get('society/login', 'HomeController@society');
Route::resource('society', 'Society\SocietyController');
Route::resource('event/{eventId}/question', 'Event\QuestionController');

Route::group(
    ['prefix' => 'api'], function() {

    Route::get('society/dashboard', 'Society\SocietyController@index');
    Route::post('society/login', 'Society\SocietyControllerApi@login');

    Route::get('user/login', 'HomeController@user');
    Route::post('user/login', 'User\UserController@login');
    Route::resource('event/{eventId}/question', 'Event\QuestionControllerApi');
    Route::post('event/{eventId}/answer/{id}', 'Event\AnswerControllerApi@store');
    Route::resource('society', 'Society\SocietyControllerApi');
    Route::resource('user', 'User\UserController');
    Route::resource('event', 'Event\EventControllerApi');

});

Route::get('password/reset', function() {
    return "helo";
});

Route::get('/logout', 'HomeController@logout');

// Api

Route::group(
    ['prefix' => 'api'], function() {

    Route::get('/', 'Event\EventController@index');
    Route::resource('event', 'Event\EventControllerApi');

});