<?php

namespace App\Admin\Controllers;

use App\Models\Feedback;

use App\Admin\Controllers\MyAdminController;

use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

use Encore\Admin\Layout\Content;

use App\Helpers\StringHelper;

use DB;

class FeedbackController extends MyAdminController {
	
	/**
	* Title for current resource.
	*
	* @var string
	*/
	protected $title = 'Зворотній зв\'язок';
	
	/**
	* Make a grid builder.
	*
	* @return Grid
	*/
	protected function grid(){
		$grid = new Grid(new Feedback());
		
		$grid->column('id'				, __('ID'));
		
		$grid->column('created_at'		, __('admin.feedback.created_at'));
		
		$grid->column('name'			, __('admin.feedback.name'));
		$grid->column('email'			, __('admin.feedback.email'));
		$grid->column('messasge'		, __('admin.feedback.messasge'));
		
		$model = $grid->model();
		
		$model->orderBy('created_at', 'desc');
		
		$grid->actions(function($actions){
			//$tools->disableDelete();
			$actions->disableView();
			//$tools->disableList();
		});
		
		$grid->filter(function($filter){
			$filter->like('name'			, __('admin.clients.name'));
			$filter->like('email'			, __('admin.clients.email'));
		});
		
		$grid->disableCreateButton();
		
		$grid->paginate(100);
		
		return $grid;
	}
	
	protected function detail($id){
		header('Location: /admin/feedback/'.$id.'/edit');
		return;
	}
	
	/**
	* Make a form builder.
	*
	* @return Form
	*/
	protected function form(){
		$form = new Form(new Feedback());
		
		$this->configure($form);
		
		$id = $this->_id;
		
		$form->text('name'			, __('admin.feedback.name'))->rules('required|min:2|max:150');
		$form->email('email'		, __('admin.feedback.email'))->rules('required|min:2|max:150');
		
		$form->textarea('messasge'	, __('admin.feedback.messasge'));
		
		// callback before save
		$form->saving(function (Form $form){
			$form->name			= trim($form->name);
		});
		
		return $form;
	}
}
