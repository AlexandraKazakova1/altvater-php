<?php

namespace App\Admin\Controllers;

use App\Models\Orders;
use App\Models\User;
use App\Models\OrdersServices;

use App\Admin\Controllers\MyAdminController;

use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

use Encore\Admin\Layout\Content;

use App\Helpers\StringHelper;

use DB;

class OrdersController extends MyAdminController {
	
	/**
	* Title for current resource.
	*
	* @var string
	*/
	protected $title = 'Замовлення';
	
	/**
	* Make a grid builder.
	*
	* @return Grid
	*/
	protected function grid(){
		$grid = new Grid(new Orders());
		
		$grid->column('id'				, __('ID'));
		
		$grid->column('created_at'		, __('admin.orders.created_at'));
		$grid->column('date'			, __('admin.orders.date'));
		
		$grid->column('status'			, __('admin.orders.status.label'))->display(function($status){
			return trans('admin.orders.status.'.$status);
		});
		
		$grid->column('service_id'		, __('admin.orders.service'))->display(function(){
			$service = $this->service;
			
			if($service){
				return '#'.$service->id.' - '.$service->name;
			}
			
			return '-';
		});
		
		$grid->column('client_id'		, __('admin.orders.client'))->display(function(){
			$client = $this->client;
			
			if($client){
				return '#'.$client->id.' - '.$client->surname.' '.$client->name;
			}
			
			return '-';
		});
		
		$model = $grid->model();
		
		$model->orderBy('created_at', 'desc');
		
		$grid->actions(function($actions){
			//$tools->disableDelete();
			$actions->disableView();
			//$tools->disableList();
		});
		
		//$grid->disableCreateButton();
		
		$grid->paginate(100);
		
		return $grid;
	}
	
	protected function detail($id){
		header('Location: /admin/orders/'.$id.'/edit');
		return;
	}
	
	/**
	* Make a form builder.
	*
	* @return Form
	*/
	protected function form(){
		$form = new Form(new Orders());
		
		$this->configure($form);
		
		$id = $this->_id;
		
		//
		
		$users = [];
		
		$tmp = User::query()->get();
		
		foreach($tmp as $item){
			$users[$item->id] = '#'.$item->id.' - '.$item->surname.' '.$item->name;
		}
		
		$form->select('client_id'	, __('admin.orders.client'))->options($users);
		
		//
		
		$services = [];
		
		$tmp = OrdersServices::query()->get();
		
		foreach($tmp as $item){
			$services[$item->id] = '#'.$item->id.' - '.$item->name;
		}
		
		$form->select('service_id'	, __('admin.orders.service'))->options($services);
		
		//
		
		$form->date('date'			, __('admin.orders.date'));
		$form->text('time'			, __('admin.orders.time'));
		
		$form->text('addresses'		, __('admin.orders.addresses'))->rules('max:200');
		
		$form->radio('status'		, __('admin.orders.status.label'))
									->options([
										'processing'	=> __('admin.orders.status.processing'),
										'performed'		=> __('admin.orders.status.performed'),
										'done'			=> __('admin.orders.status.done'),
										'scheduled'		=> __('admin.orders.status.scheduled')
									]);
		
		$form->textarea('comment'	, __('admin.orders.comment'));
		
		// callback before save
		$form->saving(function (Form $form){});
		
		$form->saved(function(Form $form){
			$id = $form->model()->id;
		});
		
		return $form;
	}
}
