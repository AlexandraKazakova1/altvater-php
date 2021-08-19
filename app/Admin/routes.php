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
	$router->resource('violation-schedule'		, ViolationController::class);
	$router->resource('applications'			, ApplicationsController::class);
	$router->resource('contracts-household'		, ContractsHouseholdController::class);
	$router->resource('contracts-construction'	, ContractsConstructionController::class);
	$router->resource('contracts-separate'		, ContractsSeparateController::class);
	$router->resource('contents'				, ContentsController::class);
	$router->resource('packages'				, PackagesController::class);
	$router->resource('templates'				, EmailTemplatesController::class);
	$router->resource('payments'				, PaymentsController::class);
	$router->resource('reviews'					, ReviewsController::class);
	$router->resource('sale-containers'			, SaleContainersController::class);
	$router->resource('purchase'				, PurchaseController::class);
	
	$router->resource('ip-list'					, IpListController::class);
	$router->resource('faq'						, FAQController::class);
	$router->resource('site-menu'				, SiteMenuController::class);
	$router->resource('pages'					, PagesController::class);
	$router->resource('news'					, NewsController::class);
	$router->resource('services'				, ServicesController::class);
});
