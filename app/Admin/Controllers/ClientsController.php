<?php

namespace App\Admin\Controllers;

use App\Models\Clients;

use App\Admin\Controllers\MyAdminController;

use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

use Encore\Admin\Layout\Content;

use App\Helpers\StringHelper;

use DB;

class ClientsController extends MyAdminController {
	
	/**
	* Title for current resource.
	*
	* @var string
	*/
	protected $title = 'Клієнти';
	
	/**
	* Make a grid builder.
	*
	* @return Grid
	*/
	protected function grid(){
		$grid = new Grid(new Clients());
		
		$grid->column('id'				, __('ID'));
		
		$grid->column('created_at'		, __('admin.clients.created_at'));
		
		$grid->column('name'			, __('admin.clients.name'));
		
		$grid->column('blocked'			, __('admin.clients.blocked'))->display(function($blocked){
			$blocked = (int)$blocked;
			
			return $blocked > 0 ? '<i class="fa fa-check" style="color:green;" aria-hidden="true"></i>' : '<i class="fa fa-times" style="color:red;" aria-hidden="true"></i>';
		});
		
		$model = $grid->model();
		
		$model->orderBy('created_at', 'desc');
		
		$grid->actions(function($actions){
			//$tools->disableDelete();
			$actions->disableView();
			//$tools->disableList();
		});
		
		$grid->filter(function($filter){
			$filter->like('name'			, __('admin.clients.name'));
			$filter->like('phone'			, __('admin.clients.phone'));
			$filter->like('email'			, __('admin.clients.email'));
			$filter->like('address'			, __('admin.clients.address'));
		});
		
		$grid->disableCreateButton();
		
		$grid->paginate(100);
		
		return $grid;
	}
	
	protected function detail($id){
		header('Location: /clients/'.$id.'/edit');
		return;
	}
	
	/**
	* Make a form builder.
	*
	* @return Form
	*/
	protected function form(){
		$form = new Form(new Clients());
		
		$this->configure($form);
		
		$id = $this->_id;
		
		$form->text('name'			, __('admin.clients.name'))->rules('required|min:2|max:100');
		
		$form->switch('blocked'		, __('admin.clients.blocked'));
		
		// callback before save
		$form->saving(function (Form $form){
			$form->name			= trim($form->name);
		});
		
		return $form;
	}
}
