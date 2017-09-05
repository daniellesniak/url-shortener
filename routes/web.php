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

use Illuminate\Session;
use App\Helpers\Geolocation;

Route::get('/', 'HomeController@index')->name('home');
Route::post('/', 'UrlController@store');
Route::get('/{string_id}/hide', 'HomeController@hideUrl');
Route::get('/my-shortens', 'MyShortensController@index');

Route::get('/{id}', 'UrlController@redirect');
Route::get('/{id}/stats', 'UrlController@stats');