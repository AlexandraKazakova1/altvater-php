<?php

namespace App\Admin\Controllers;

use App\Models\SaleContainers;

use App\Admin\Controllers\MyAdminController;

use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

use Encore\Admin\Layout\Content;

use DB;

class SaleContainersController extends MyAdminController {
	
	/**
	 * Title for current resource.
	 *
	 * @var string
	 */
	protected $title = 'Продаж контейнерів';

	/**
	 * Make a grid builder.
	 *
	 * @return Grid
	 */
	protected function grid(){
		$grid = new Grid(new SaleContainers());
		
		$grid->column('id'				, __('ID'));
		
		$grid->column('created_at'		, __('admin.sale_containers.created_at'));
		$grid->column('client'			, __('admin.sale_containers.client'))->display(function(){
			$client = $this->client;
			
			return '#'.$client->id.' - '.$client->name;
		});
		$grid->column('phone'			, __('admin.sale_containers.phone'));
		$grid->column('email'			, __('admin.sale_containers.email'));
		$grid->column('name'			, __('admin.sale_containers.name'));
		
		$grid->column('count'			, __('admin.sale_containers.count'));
		
		$grid->column('color'			, __('admin.sale_containers.color'))->display(function($color){
			return __('admin.sale_containers.'.$color);
		});
		
		$grid->column('value'			, __('admin.sale_containers.value'))->display(function($value){
			return $value.' '.__('admin.sale_containers.liters');
		});
		
		$model = $grid->model();
		
		$grid->actions(function($actions){
			//$tools->disableDelete();
			$actions->disableView();
			//$tools->disableList();
		});
		
		$model->orderBy('created_at', 'desc');
		
		$grid->filter(function($filter){
			$filter->like('name'			, __('admin.sale_containers.name'));
			$filter->like('email'			, __('admin.sale_containers.email'));
			$filter->like('phone'			, __('admin.sale_containers.phone'));
		});
		
		$grid->disableCreateButton();
		
		$grid->paginate(100);
		
		return $grid;
	}
	
	protected function detail($id){
		header('Location: /sale-containers/'.$id.'/edit');
		return;
	}
	
	/**
	 * Make a form builder.
	 *
	 * @return Form
	 */
	protected function form(){
		$form = new Form(new SaleContainers());
		
		$this->configure($form);
		
		$id = $this->_id;
		
		$form->decimal('count'			, __('admin.sale_containers.count'))->rules('required');
		
		$form->radio('color'			, __('admin.sale_containers.color'))
					->options([
						'gray'			=> __('admin.sale_containers.gray'),
						'green'			=> __('admin.sale_containers.green'),
						'blue'			=> __('admin.sale_containers.blue'),
					]);
		
		$form->radio('value'			, __('admin.sale_containers.value'))
					->options([
						'120'			=> '120'.' '.__('admin.sale_containers.liters'),
						'240'			=> '240'.' '.__('admin.sale_containers.liters'),
						'1100'			=> '1100'.' '.__('admin.sale_containers.liters'),
					]);
		
		$form->text('name'			, __('admin.sale_containers.name'))->rules('max:150|required');
		
		$form->text('phone'			, __('admin.sale_containers.phone'))->rules('min:9|max:30');
		$form->email('email'		, __('admin.sale_containers.email'))->rules('max:100');
		
		return $form;
	}
}
