<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::namespace('API')->group(function(){
	
	Route::get('/bas/sync'				, 'BasController@sync')->name('bas-get');
	Route::post('/bas/sync'				, 'BasController@sync')->name('bas-post');
	
});
