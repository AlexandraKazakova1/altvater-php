<?php

namespace App\Admin\Controllers;

use App\Models\Violation;

use App\Admin\Controllers\MyAdminController;

use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

use Encore\Admin\Layout\Content;

use App\Helpers\StringHelper;

use DB;

class ViolationController extends MyAdminController {
	
	/**
	* Title for current resource.
	*
	* @var string
	*/
	protected $title = 'Порушення графіку';
	
	/**
	* Make a grid builder.
	*
	* @return Grid
	*/
	protected function grid(){
		$grid = new Grid(new Violation());
		
		$grid->column('id'				, __('ID'));
		
		$grid->column('created_at'		, __('admin.violation.created_at'));
		
		$grid->column('name'			, __('admin.violation.name'));
		
		$grid->column('type'			, __('admin.violation.type'))->display(function($type){
			if($type){
				return __('admin.violation.type_'.$type);
			}
			
			return '-';
		});
		
		$grid->column('address'			, __('admin.violation.address'));
		
		$model = $grid->model();
		
		$model->orderBy('created_at', 'desc');
		
		$grid->actions(function($actions){
			//$tools->disableDelete();
			$actions->disableView();
			//$tools->disableList();
		});
		
		$grid->filter(function($filter){
			$filter->like('name'			, __('admin.clients.name'));
			$filter->like('address'			, __('admin.clients.address'));
		});
		
		$grid->disableCreateButton();
		
		$grid->paginate(100);
		
		return $grid;
	}
	
	protected function detail($id){
		header('Location: /violation-schedule/'.$id.'/edit');
		return;
	}
	
	/**
	* Make a form builder.
	*
	* @return Form
	*/
	protected function form(){
		$form = new Form(new Violation());
		
		$this->configure($form);
		
		$id = $this->_id;
				
		$form->tab(__('admin.violation.info')		, function($form) use ($id){
			$form->text('name'			, __('admin.violation.name'))->rules('required|min:2|max:100');
			
			$form->text('address'		, __('admin.violation.address'))->rules('required|max:150');
			
			$form->radio('type'			, __('admin.violation.type'))->options(['household' => __('admin.violation.type_household'), 'separate' => __('admin.violation.type_separate')])->rules('required');
		});
		
		$form->tab(__('admin.violation.photos')		, function($form) use ($id){
			$form->hasMany('photos', '', function($form){
				$form->image('image'			, __('admin.violation.image'))->removable()->move('violation-images')->uniqueName();
			});
		});
		
		if($this->_edit){
			if(!$this->_update){}
		}
		
		// callback before save
		$form->saving(function (Form $form){
			$form->name			= trim($form->name);
		});
		
		return $form;
	}
}
