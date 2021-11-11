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
	
	Route::post('ajax/user/registration', [
		'uses' => 'UserController@registration'
	]);
	
	Route::post('ajax/user/verification', [
		'uses' => 'UserController@verification'
	]);
	
	Route::post('ajax/user/recovery', [
		'uses' => 'UserController@forgotten'
	]);
	
	Route::post('ajax/user/settings', [
		'uses' => 'UserController@settings'
	]);
	
	Route::post('ajax/user/new-password', [
		'uses' => 'UserController@new_password'
	]);
	
	Route::post('ajax/user/change-password', [
		'uses' => 'UserController@change_password'
	]);
	
	Route::post('ajax/cabinet/order-service', [
		'uses' => 'OrdersController@add'
	]);
	
	Route::post('ajax/cabinet/contracts/add', [
		'uses' => 'ContractsController@add'
	]);
	
	Route::post('ajax/cabinet/contracts/connect', [
		'uses' => 'ContractsController@connect_contract'
	]);
	
	Route::get('ajax/cabinet/contracts/{type}', [
		'uses' => 'ContractsController@contracts_list'
	])->where(['type' => '[a-z]+']);
	
	Route::get('ajax/cabinet/bills', [
		'uses' => 'BillsController@index'
	]);
	
	Route::get('ajax/cabinet/acts', [
		'uses' => 'ActsController@index'
	]);
	
	Route::post('ajax/cabinet/add-address', [
		'uses' => 'AddressController@add'
	]);
	
	Route::post('ajax/cabinet/remove-address', [
		'uses' => 'AddressController@remove'
	]);
	
	Route::post('ajax/user/address', [
		'uses' => 'UserController@address'
	]);
	
	Route::post('ajax/cabinet/request', [
		'uses' => 'MessagesController@add'
	]);
	
	Route::post('ajax/cabinet/request/{id}', [
		'uses' => 'MessagesController@message'
	])->where('id', '[0-9]+');
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

Route::get('account/contract/{id}', [
	'as'    => 'contract',
	'uses'  => 'AccountController@contract'
])->where('id', '[0-9]+');

Route::get('account/bills', [
	'as'    => 'bills',
	'uses'  => 'AccountController@bills'
]);

Route::get('account/bills/{id}', [
	'as'    => 'bill',
	'uses'  => 'AccountController@bill'
])->where('id', '[0-9]+');

Route::get('account/bills/act/{id}', [
	'as'    => 'act',
	'uses'  => 'AccountController@act'
])->where('id', '[0-9]+');


Route::get('account/messages', [
	'as'    => 'messages',
	'uses'  => 'AccountController@messages'
]);

Route::get('account/messages/{id}', [
	'as'    => 'dialog',
	'uses'  => 'AccountController@dialog'
])->where('id', '[0-9]+');

Route::get('account/orders', [
	'as'    => 'orders',
	'uses'  => 'AccountController@orders'
]);

Route::get('account/settings', [
	'as'    => 'settings',
	'uses'  => 'AccountController@settings'
]);

Route::get('account/logout', [
	'as'    => 'logout',
	'uses'  => 'AccountController@logout'
]);

Route::get('reset/{code}', [
	'uses' => 'PageController@index'
])->where('code', '[a-zA-Z_0-9\-]+');

Route::get('/{uri}', [
	'uses' => 'PageController@once'
])->where('uri', '[a-zA-Z_0-9\-]+');
