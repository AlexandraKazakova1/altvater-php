<?php

namespace App\Admin\Controllers;

use App\Models\OrdersServices;

use App\Admin\Controllers\MyAdminController;

use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

use DB;

class OrdersServicesController extends MyAdminController {
	
	/**
	 * Title for current resource.
	 *
	 * @var string
	 */
	protected $title = 'Послуги для замовлення';
	
	/**
	 * Make a grid builder.
	 *
	 * @return Grid
	 */
	protected function grid(){
		$grid = new Grid(new OrdersServices());
		
		$grid->column('id'				, __('ID'));
		
		$grid->column('sort'			, __('admin.orders_services.sort'));
		
		$grid->column('active'			, __('admin.orders_services.active'))->display(function($active){
			$active = (int)$active;
			
			return $active > 0 ? '<i class="fa fa-check" style="color:green;" aria-hidden="true"></i>' : '<i class="fa fa-times" style="color:red;" aria-hidden="true"></i>';
		});
		
		$grid->column('name'			, __('admin.orders_services.name'));
		
		$grid->actions(function($actions){
			//$tools->disableDelete();
			$actions->disableView();
			//$tools->disableList();
		});
		
		$grid->paginate(100);
		
		return $grid;
	}
	
	protected function detail($id){
		header('Location: /admin/orders_services/'.$id.'/edit');
		return;
	}
	
	/**
	 * Make a form builder.
	 *
	 * @return Form
	 */
	protected function form(){
		$form = new Form(new OrdersServices());
		
		$this->configure($form);
		
		$form->decimal('sort'			, __('admin.orders_services.sort'));
		
		$form->switch('active'			, __('admin.orders_services.active'));
		
		$form->text('name'				, __('admin.orders_services.name'))->rules('required|min:3|max:150');
		
		$form->saving(function(Form $form){
			$form->sort			= (int)trim($form->sort);
			
			if($form->sort < 1){
				$count = DB::table('orders_services')->count();
				$count++;
				
				$form->sort = $count;
			}
		});
		
		return $form;
	}
}
