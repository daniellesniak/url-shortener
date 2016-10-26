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

Route::get('/', 'HomeController@index');
Route::post('/', 'UrlController@store');

Route::get('/{id}', 'UrlController@redirect');

Route::get('/{id}/stats', 'UrlController@statsv2');

Route::get('test/1', function () {
	return phpinfo();
});