<?php

namespace App\Admin\Controllers;

use App\Models\Packages;

use App\Admin\Controllers\MyAdminController;

use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class PackagesController extends MyAdminController {
	
	/**
	 * Title for current resource.
	 *
	 * @var string
	 */
	protected $title = 'Спец.пакети';
	
	/**
	 * Make a grid builder.
	 *
	 * @return Grid
	 */
	protected function grid(){
		$grid = new Grid(new Packages());
		
		$grid->column('id'				, __('ID'));
		
		$grid->column('sort'			, __('admin.packages.sort'))->sortable();
		
		$grid->column('created_at'		, __('admin.packages.created_at'));
		$grid->column('updated_at'		, __('admin.packages.updated_at'));
		
		$grid->column('public'			, __('admin.public'))->display(function($public){
			$public = (int)$public;
			
			return $public > 0 ? '<i class="fa fa-check" style="color:green;" aria-hidden="true"></i>' : '<i class="fa fa-times" style="color:red;" aria-hidden="true"></i>';
		});
		
		$grid->column('name'			, __('admin.packages.name'))->sortable();
		$grid->column('price'			, __('admin.packages.price'))->sortable();
		
		$model = $grid->model();
		
		$model->orderBy('sort', 'asc');
		
		$grid->actions(function($actions){
			//$tools->disableDelete();
			$actions->disableView();
			//$tools->disableList();
		});
		
		return $grid;
	}
	
	protected function detail($id){
		header('Location: /packages/'.$id.'/edit');
		return;
	}
	
	/**
	 * Make a form builder.
	 *
	 * @return Form
	 */
	protected function form(){
		$form = new Form(new Packages());
		
		$this->configure($form);
		
		$id = (int)request()->segment(3);
		
		$form->decimal('sort'			, __('admin.packages.sort'));
		
		$form->switch('public'			, __('admin.packages.public'));
		
		$form->text('name'				, __('admin.packages.name'))->rules('required|max:10');
		$form->decimal('price'			, __('admin.packages.price'))->rules('required');
		
		return $form;
	}
}
