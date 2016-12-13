<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are handled
| by your application. Just tell Laravel the URIs it should respond
| to using a Closure or controller method. Build something great!
|
*/


Auth::routes();

Route::get('/', ['as' => 'home', 'uses' => 'HomeController@index']);

Route::get('/my-account', ['as' => 'myAccount', 'uses' => 'UsersController@myAccount']);
Route::get('/calendar/{id}/{calendar_id}', ['as' => 'calendar', 'uses' => 'CalendarsController@calendar']);
Route::get('/calendars', ['as' => 'calendars', 'uses' => 'CalendarsController@index']);
Route::post('/calendar/add', ['as' => 'addCalendar', 'uses' => 'CalendarsController@store']);
Route::get('/oauth2redirect', ['as' => 'getAccessToken', 'uses' => 'CalendarsController@getAccessToken']);

Route::post('/update-access-token', ['as' => 'UpdateAccessToken', 'uses' => 'CalendarsController@updateAccessToken']);