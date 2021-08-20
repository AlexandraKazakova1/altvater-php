<?php

namespace App\Admin\Controllers;

use App\Models\Purchase;

use App\Admin\Controllers\MyAdminController;

use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

use Encore\Admin\Layout\Content;

use DB;

class PurchaseController extends MyAdminController {
	
	/**
	 * Title for current resource.
	 *
	 * @var string
	 */
	protected $title = 'Закупівля вторинних ресурсів';

	/**
	 * Make a grid builder.
	 *
	 * @return Grid
	 */
	protected function grid(){
		$grid = new Grid(new Purchase());
		
		$grid->column('id'				, __('ID'));
		
		$grid->column('created_at'		, __('admin.purchase.created_at'));
		$grid->column('client'			, __('admin.purchase.client'))->display(function(){
			$client = $this->client;
			
			return '#'.$client->id.' - '.$client->name;
		});
		
		$grid->column('phone'			, __('admin.purchase.phone'));
		$grid->column('email'			, __('admin.purchase.email'));
		$grid->column('name'			, __('admin.purchase.name'));
		
		$grid->column('address'			, __('admin.purchase.address'));
		
		$grid->column('weight'			, __('admin.purchase.weight'));
		
		$grid->column('type'			, __('admin.purchase.type'))->display(function($type){
			return __('admin.purchase.'.$type);
		});
		
		$model = $grid->model();
		
		$grid->actions(function($actions){
			//$tools->disableDelete();
			$actions->disableView();
			//$tools->disableList();
		});
		
		$model->orderBy('created_at', 'desc');
		
		$grid->filter(function($filter){
			$filter->like('name'			, __('admin.purchase.name'));
			$filter->like('email'			, __('admin.purchase.email'));
			$filter->like('phone'			, __('admin.purchase.phone'));
		});
		
		$grid->disableCreateButton();
		
		$grid->paginate(100);
		
		return $grid;
	}
	
	protected function detail($id){
		header('Location: /purchase/'.$id.'/edit');
		return;
	}
	
	/**
	 * Make a form builder.
	 *
	 * @return Form
	 */
	protected function form(){
		$form = new Form(new Purchase());
		
		$this->configure($form);
		
		$id = $this->_id;
		
		$form->text('address'			, __('admin.purchase.address'))->rules('max:150|required');
		
		$form->text('weight'			, __('admin.purchase.weight'))->rules('max:30|required');
		
		$form->radio('type'			, __('admin.purchase.type'))
					->options([
						'wastepaper'			=> __('admin.purchase.wastepaper'),
						'pet-bottle'			=> __('admin.purchase.pet-bottle'),
						'pet-film'				=> __('admin.purchase.pet-film'),
						'metal'					=> __('admin.purchase.metal'),
						'glass'					=> __('admin.purchase.glass'),
					]);
		
		$form->text('name'			, __('admin.purchase.name'))->rules('max:100|required');
		
		$form->text('phone'			, __('admin.purchase.phone'))->rules('min:9|max:30');
		
		$form->email('email'		, __('admin.purchase.email'))->rules('max:150');
		
		return $form;
	}
}
