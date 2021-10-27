<?php

namespace App\Admin\Controllers;

use App\Models\CalcObject;

use App\Admin\Controllers\MyAdminController;

use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

use DB;

class CalcObjectController extends MyAdminController {
	
	/**
	 * Title for current resource.
	 *
	 * @var string
	 */
	protected $title = 'Об\'єкт утворення ТПВ';
	
	/**
	 * Make a grid builder.
	 *
	 * @return Grid
	 */
	protected function grid(){
		$grid = new Grid(new CalcObject());
		
		$grid->column('sort'			, __('admin.calc-object.sort'));
		
		$grid->column('name'			, __('admin.calc-object.name'));
		$grid->column('label'			, __('admin.calc-object.label'));
		$grid->column('value'			, __('admin.calc-object.value'));
		
		$grid->actions(function($actions){
			//$tools->disableDelete();
			$actions->disableView();
			//$tools->disableList();
		});
		
		$grid->paginate(100);
		
		$grid->model()->orderBy('sort', 'asc');
		
		return $grid;
	}
	
	protected function detail($id){
		header('Location: /admin/calc-object/'.$id.'/edit');
		return;
	}
	
	/**
	 * Make a form builder.
	 *
	 * @return Form
	 */
	protected function form(){
		$form = new Form(new CalcObject());
		
		$this->configure($form);
		
		$form->decimal('sort'			, __('admin.calc-object.sort'));
		
		$form->text('name'				, __('admin.calc-object.name'))->rules('required|min:3|max:150');
		$form->text('label'				, __('admin.calc-object.label'))->rules('required|min:3|max:100');
		$form->decimal('value'			, __('admin.calc-object.value'))->rules('required');
		
		$form->saving(function(Form $form){
			$form->sort			= (int)trim($form->sort);
			
			if($form->sort < 1){
				$count = DB::table('calc_object')->count();
				$count++;
				
				$form->sort = $count;
			}
		});
		
		return $form;
	}
}
