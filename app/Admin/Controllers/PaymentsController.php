<?php

namespace App\Admin\Controllers;

use App\Models\Payments;
use App\Models\Packages;

use App\Admin\Controllers\MyAdminController;

use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

use Encore\Admin\Layout\Content;

use App\Helpers\StringHelper;

use DB;

class PaymentsController extends MyAdminController {
	
	/**
	* Title for current resource.
	*
	* @var string
	*/
	protected $title = 'Оплати';
	
	/**
	* Make a grid builder.
	*
	* @return Grid
	*/
	protected function grid(){
		$grid = new Grid(new Payments());
		
		$grid->column('id'				, __('ID'));
		
		$grid->column('created_at'		, __('admin.payments.created_at'));
		
		$grid->column('name'			, __('admin.payments.name'));
		
		$grid->column('type'			, __('admin.payments.type'))->display(function($type){
			if($type){
				return __('admin.payments.type_'.$type);
			}
			
			return '-';
		});
		
		$grid->column('address'			, __('admin.payments.address'));
		$grid->column('email'			, __('admin.payments.email'));
		
		$model = $grid->model();
		
		$model->orderBy('created_at', 'desc');
		
		$grid->actions(function($actions){
			//$tools->disableDelete();
			$actions->disableView();
			//$tools->disableList();
		});
		
		$grid->filter(function($filter){
			$filter->like('name'			, __('admin.payments.name'));
			$filter->like('address'			, __('admin.payments.address'));
			$filter->like('email'			, __('admin.payments.email'));
		});
		
		$grid->disableCreateButton();
		
		$grid->paginate(100);
		
		return $grid;
	}
	
	protected function detail($id){
		header('Location: /payments/'.$id.'/edit');
		return;
	}
	
	/**
	* Make a form builder.
	*
	* @return Form
	*/
	protected function form(){
		$form = new Form(new Payments());
		
		$this->configure($form);
		
		$id = $this->_id;
		
		$form->radio('type'			, __('admin.payments.type'))
				->options([
					'physical'		=> __('admin.payments.type_physical'),
					'juridical'		=> __('admin.payments.type_juridical')
				])
				->rules('required');
		
		$form->text('name'			, __('admin.payments.name'))->rules('required|min:2|max:100');
		$form->text('address'		, __('admin.payments.address'))->rules('max:150');
		$form->email('email'		, __('admin.payments.email'))->rules('max:50');
		
		$packages = [
			null => '-'
		];
		
		$tmp = Packages::orderBy('sort', 'asc')->get();
		
		foreach($tmp as $item){
			$packages[$item->id] = $item->name;
		}
		
		$form->select('package_id'			, __('admin.payments.package'))->options($packages);
		
		$form->decimal('count_packages'		, __('admin.payments.count_packages'));
		$form->decimal('amount'				, __('admin.payments.amount'));
		
		// callback before save
		$form->saving(function (Form $form){
			$form->name			= trim($form->name);
			
			$form->phone		= preg_replace("/[^0-9]/", '', $form->phone);
		});
		
		return $form;
	}
}
