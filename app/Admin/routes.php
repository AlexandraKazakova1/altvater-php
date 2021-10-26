<?php

use Illuminate\Routing\Router;

Admin::routes();

Route::group([
	'prefix'        => config('admin.route.prefix'),
	'namespace'     => config('admin.route.namespace'),
	'middleware'    => config('admin.route.middleware'),
	//'as'            => config('admin.route.prefix') . '.',
], function (Router $router) {
	$router->get('/'							, 'HomeController@index')->name('home');
	$router->get('/auth/logout'					, 'AuthController@logout')->name('admin.logout');
	
	$router->resource('clients'					, ClientsController::class);
	
	$router->resource('contents'				, ContentsController::class);
	$router->resource('packages'				, PackagesController::class);
	$router->resource('templates'				, EmailTemplatesController::class);
	$router->resource('reviews'					, ReviewsController::class);
	
	$router->resource('ip-list'					, IpListController::class);
	$router->resource('faq'						, FAQController::class);
	$router->resource('site-menu'				, SiteMenuController::class);
	$router->resource('footer-menu'				, FooterMenuController::class);
	$router->resource('pages'					, PagesController::class);
	$router->resource('news'					, NewsController::class);
	$router->resource('services'				, ServicesController::class);
	$router->resource('feedback'				, FeedbackController::class);
	
	$router->resource('contracts'				, ContractsController::class);
	$router->resource('bills'					, BillsController::class);
	$router->resource('acts'					, ActsController::class);
	
	$router->resource('orders_services'			, OrdersServicesController::class);
	
	$router->resource('orders'					, OrdersController::class);
	
	//$router->resource('addresses'				, AddressesController::class);
	
	$router->resource('calc-object'				, CalcObjectController::class);
});
