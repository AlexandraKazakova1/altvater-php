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
	Route::post('ajax/callback', [
		'uses' => 'SendController@contact'
	]);
	
	Route::post('ajax/user/login', [
		'uses' => 'UserController@login'
	]);
	
	Route::post('ajax/user/register', [
		'uses' => 'UserController@register'
	]);
	
	Route::post('ajax/user/activation', [
		'uses' => 'UserController@activation'
	]);
	
	Route::post('ajax/user/forgotten', [
		'uses' => 'UserController@forgotten'
	]);
	
	Route::post('ajax/user/profile', [
		'uses' => 'UserController@profile'
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

Route::get('services', [
	'as'    => 'services',
	'uses'  => 'ServicesController@index'
]);

Route::get('services/{uri}', [
	'as'    => 'services_once',
	'uses'  => 'ServicesController@once'
])->where('uri', '[a-zA-Z_0-9\-]+');

Route::get('sitemap.html', [
	'as'    => 'sitemap',
	'uses'  => 'SitemapController@index'
]);

Route::get('about', [
	'as'    => 'about',
	'uses'  => 'AboutController@index'
]);

Route::get('account', [
	'as'    => 'account',
	'uses'  => 'AccountController@index'
]);

Route::get('account/contracts', [
	'as'    => 'contracts',
	'uses'  => 'AccountController@contracts'
]);

Route::get('account/bills', [
	'as'    => 'bills',
	'uses'  => 'AccountController@bills'
]);

Route::get('account/messages', [
	'as'    => 'messages',
	'uses'  => 'AccountController@messages'
]);

Route::get('/{uri}', [
	'uses' => 'PageController@once'
])->where('uri', '[a-zA-Z_0-9\-]+');
