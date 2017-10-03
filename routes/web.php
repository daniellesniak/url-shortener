<?php

Route::get('/', 'ShortenController@index')->name('home');

Route::post('/shorten', 'ShortenController@store');
Route::get('/{slug}/hide', 'ShortenController@hideShorten');

Route::get('/{slug}/stats', 'ShortenController@stats');
Route::get('/{slug}', 'ShortenController@redirect');