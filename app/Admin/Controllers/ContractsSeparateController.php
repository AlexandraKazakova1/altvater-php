<?php

namespace App\Admin\Controllers;

use App\Models\Contracts;

use App\Admin\Controllers\MyAdminController;

use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

use Encore\Admin\Layout\Content;

use App\Helpers\StringHelper;

use DB;

class ContractsSeparateController extends MyAdminController {
	
	/**
	* Title for current resource.
	*
	* @var string
	*/
	protected $title = 'Договори - роздільне збирання';
	
	/**
	* Make a grid builder.
	*
	* @return Grid
	*/
	protected function grid(){
		$grid = new Grid(new Contracts());
		
		$grid->column('id'				, __('ID'));
		
		$grid->column('created_at'		, __('admin.contracts.created_at'));
		
		$grid->column('name'			, __('admin.contracts.name'));
		$grid->column('address'			, __('admin.contracts.address'));
		$grid->column('phone'			, __('admin.contracts.phone'));
		
		$grid->column('count_containers', __('admin.contracts.count_containers'));
		$grid->column('period'			, __('admin.contracts.period'));
		
		$model = $grid->model();
		
		$model->orderBy('created_at', 'desc');
		
		$model->where('type', 'separate');
		
		$grid->actions(function($actions){
			//$tools->disableDelete();
			$actions->disableView();
			//$tools->disableList();
		});
		
		$grid->filter(function($filter){
			$filter->like('name'			, __('admin.contracts.name'));
			$filter->like('phone'			, __('admin.contracts.phone'));
			$filter->like('email'			, __('admin.contracts.email'));
			$filter->like('address'			, __('admin.contracts.address'));
		});
		
		$grid->disableCreateButton();
		
		return $grid;
	}
	
	protected function detail($id){
		header('Location: /contracts-separate/'.$id.'/edit');
		return;
	}
	
	/**
	* Make a form builder.
	*
	* @return Form
	*/
	protected function form(){
		$form = new Form(new Contracts());
		
		$this->configure($form);
		
		$id = $this->_id;
		
		$form->hidden('type');
		
		$this->configure($form);
		
		$form->decimal('count_containers'		, __('admin.contracts.count_containers'))->rules('required');
		
		$form->radio('period'		, __('admin.contracts.period'))
				->options([
					1			=> 1,
					2			=> 2,
					3			=> 3,
					4			=> 4,
					5			=> 5,
					6			=> 6
				])
				->rules('required');
		
		$form->text('address'		, __('admin.contracts.address'))->rules('required|max:150');
		$form->text('name'			, __('admin.contracts.name'))->rules('required|min:2|max:100');
		
		$form->text('phone'			, __('admin.contracts.phone'))->rules('min:9|max:30');
		
		$form->email('email'		, __('admin.contracts.email'))->rules('max:50');
		
		// callback before save
		$form->saving(function (Form $form){
			$form->name			= trim($form->name);
			$form->type			= 'separate';
			
			$form->phone		= preg_replace("/[^0-9]/", '', $form->phone);
		});
		
		return $form;
	}
}
