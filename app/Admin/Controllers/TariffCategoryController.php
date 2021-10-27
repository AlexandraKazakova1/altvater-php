<?php

namespace App\Admin\Controllers;

use App\Models\TariffCategory;

use App\Admin\Controllers\MyAdminController;

use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

use DB;

class TariffCategoryController extends MyAdminController {
	
	/**
	 * Title for current resource.
	 *
	 * @var string
	 */
	protected $title = 'Категорія тарифу';
	
	/**
	 * Make a grid builder.
	 *
	 * @return Grid
	 */
	protected function grid(){
		$grid = new Grid(new TariffCategory());
		
		$grid->column('sort'			, __('admin.tariff_category.sort'));
		
		$grid->column('name'			, __('admin.tariff_category.name'));
		$grid->column('value'			, __('admin.tariff_category.value'));
		
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
		header('Location: /admin/tariff_category/'.$id.'/edit');
		return;
	}
	
	/**
	 * Make a form builder.
	 *
	 * @return Form
	 */
	protected function form(){
		$form = new Form(new TariffCategory());
		
		$this->configure($form);
		
		$form->decimal('sort'			, __('admin.tariff_category.sort'));
		
		$form->text('name'				, __('admin.tariff_category.name'))->rules('required|min:3|max:150');
		
		$form->decimal('value'			, __('admin.tariff_category.value'))->rules('required');
		
		$form->saving(function(Form $form){
			$form->sort			= (int)trim($form->sort);
			
			if($form->sort < 1){
				$count = DB::table('tariff_category')->count();
				$count++;
				
				$form->sort = $count;
			}
		});
		
		return $form;
	}
}
