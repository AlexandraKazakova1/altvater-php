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

class ContractsConstructionController extends MyAdminController {
	
	/**
	* Title for current resource.
	*
	* @var string
	*/
	protected $title = 'Договори - будівельні/великогабаритні відходи';
	
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
		
		$grid->column('volume'			, __('admin.contracts.volume'));
		
		$model = $grid->model();
		
		$model->orderBy('created_at', 'desc');
		
		$model->where('type', 'construction');
		
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
		
		$grid->paginate(100);
		
		return $grid;
	}
	
	protected function detail($id){
		header('Location: /contracts-construction/'.$id.'/edit');
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
		
		$form->radio('volume'		, __('admin.applications.volume'))
					->options([
						'8-11'			=> '8-11',
						'15-17'			=> '15-17',
						'23-25'			=> '23-25',
						'30-35'			=> '30-35',
					])
					->rules('required');
		
		$form->text('address'		, __('admin.contracts.address'))->rules('required|max:150');
		$form->text('name'			, __('admin.contracts.name'))->rules('required|min:2|max:100');
		
		$form->text('phone'			, __('admin.contracts.phone'))->rules('min:9|max:30');
		
		$form->email('email'		, __('admin.contracts.email'))->rules('max:50');
		
		if($this->_edit){
			if(!$this->_update){}
		}
		
		// callback before save
		$form->saving(function (Form $form){
			$form->name			= trim($form->name);
			$form->type			= 'construction';
			
			$form->phone		= preg_replace("/[^0-9]/", '', $form->phone);
		});
		
		return $form;
	}
}
