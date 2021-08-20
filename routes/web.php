<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Routing\Router;

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

Route::get('/', [
	'as'    => 'home',
	'uses'  => 'PageController@index'
]);

Route::group(['namespace' => 'Ajax'], function(){
	// admin
	Route::get('ajax/admin/{action}', [
		'uses' => 'AdminController@index'
	])->where(['action' => '[a-zA-Z_]+']);
	
	// feedback
	Route::post('ajax/send/contact', [
		'uses' => 'SendController@contact'
	]);
});

Route::get('news', [
	'as'    => 'news',
	'uses'  => 'NewsController@index'
]);

Route::get('news/{uri}', [
	'uses' => 'NewsController@once'
])->where('uri', '[a-zA-Z_0-9\-]+');

Route::get('contacts', [
	'as'    => 'contacts',
	'uses'  => 'ContactsController@index'
]);

Route::get('services/{uri}', [
	'as'    => 'services',
	'uses'  => 'ServicesController@index'
])->where('uri', '[a-zA-Z_0-9\-]+');

Route::get('/{uri}', [
	'uses' => 'PageController@once'
])->where('uri', '[a-zA-Z_0-9\-]+');
