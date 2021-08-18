<?php

namespace App\Admin\Controllers;

use App\Models\Contents;

use App\Admin\Controllers\MyAdminController;

use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class ContentsController extends MyAdminController {
	
	/**
	 * Title for current resource.
	 *
	 * @var string
	 */
	protected $title = 'Контент';
	
	/**
	 * Make a grid builder.
	 *
	 * @return Grid
	 */
	protected function grid(){
		$grid = new Grid(new Contents());
		
		$grid->column('id'				, __('ID'));
		
		$grid->column('created_at'		, __('admin.contents.created_at'));
		$grid->column('updated_at'		, __('admin.contents.updated_at'));
				
		$grid->column('title'			, __('admin.contents.title'));
		
		$grid->actions(function($actions){
			//$tools->disableDelete();
			$actions->disableView();
			//$tools->disableList();
		});
		
		$grid->paginate(100);
		
		return $grid;
	}
	
	protected function detail($id){
		header('Location: /contents/'.$id.'/edit');
		return;
	}
	
	/**
	 * Make a form builder.
	 *
	 * @return Form
	 */
	protected function form(){
		$form = new Form(new Contents());
		
		$this->configure($form);
		
		$form->text('title'				, __('admin.contents.title'))->rules('required|min:3|max:150');
		$form->textarea('text'			, __('admin.contents.text'));
		
		return $form;
	}
}
