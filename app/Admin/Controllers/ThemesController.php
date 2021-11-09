<?php

namespace App\Admin\Controllers;

use App\Models\Themes;

use App\Admin\Controllers\MyAdminController;

use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

use DB;

class ThemesController extends MyAdminController {
	
	/**
	 * Title for current resource.
	 *
	 * @var string
	 */
	protected $title = 'Теми звернень';
	
	/**
	 * Make a grid builder.
	 *
	 * @return Grid
	 */
	protected function grid(){
		$grid = new Grid(new Themes());
		
		$grid->column('label'			, __('admin.themes.label'));
		
		$grid->actions(function($actions){
			//$tools->disableDelete();
			$actions->disableView();
			//$tools->disableList();
		});
		
		$grid->paginate(100);
		
		return $grid;
	}
	
	protected function detail($id){
		header('Location: /admin/themes/'.$id.'/edit');
		return;
	}
	
	/**
	 * Make a form builder.
	 *
	 * @return Form
	 */
	protected function form(){
		$form = new Form(new Themes());
		
		$this->configure($form);
		
		$form->text('label'				, __('admin.themes.label'))->rules('required|min:3|max:200');
		
		$form->saving(function(Form $form){
		});
		
		return $form;
	}
}
