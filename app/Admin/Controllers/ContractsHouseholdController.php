<?php

namespace App\Admin\Controllers;

use App\Models\Contracts;

use App\Admin\Controllers\MyAdminController;

use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

use Encore\Admin\Layout\Content;

use App\Helpers\StringHelper;

use DB;

class ContractsHouseholdController extends MyAdminController {
	
	/**
	* Title for current resource.
	*
	* @var string
	*/
	protected $title = 'Договори - побутові відходи';
	
	/**
	* Make a grid builder.
	*
	* @return Grid
	*/
	protected function grid(){
		$grid = new Grid(new Contracts());
		
		$grid->column('id'				, __('ID'));
		
		$grid->column('created_at'		, __('admin.contracts.created_at'));
		
		$grid->column('name'			, __('admin.contracts.name'));
		$grid->column('address'			, __('admin.contracts.address'));
		$grid->column('phone'			, __('admin.contracts.phone'));
		
		$grid->column('container'		, __('admin.contracts.container'))->display(function($container){
			if($container){
				return __('admin.contracts.container_'.$container);
			}
			
			return '-';
		});
		
		$grid->column('volume'			, __('admin.contracts.volume'))->display(function($volume){
			if($volume == '<1.1'){
				return __('admin.contracts.volume-min');
			}
			
			if($volume == '1.1'){
				return __('admin.contracts.volume-norm');
			}
			
			return '-';
		});
		
		$grid->column('count_containers', __('admin.contracts.count_containers'));
		$grid->column('period'			, __('admin.contracts.period'));
		
		$model = $grid->model();
		
		$model->orderBy('created_at', 'desc');
		
		$model->where('type', 'household');
		
		$grid->actions(function($actions){
			//$tools->disableDelete();
			$actions->disableView();
			//$tools->disableList();
		});
		
		$grid->filter(function($filter){
			$filter->like('name'			, __('admin.contracts.name'));
			$filter->like('phone'			, __('admin.contracts.phone'));
			$filter->like('address'			, __('admin.contracts.address'));
		});
		
		$grid->disableCreateButton();
		
		$grid->paginate(100);
		
		return $grid;
	}
	
	protected function detail($id){
		header('Location: /contracts-household/'.$id.'/edit');
		return;
	}
	
	/**
	* Make a form builder.
	*
	* @return Form
	*/
	protected function form(){
		$form = new Form(new Contracts());
		
		$this->configure($form);
		
		$id = $this->_id;
		
		$form->hidden('type');
		
		$this->configure($form);
		
		$form->radio('container'	, __('admin.contracts.container'))
				->options([
					'in-stock'		=> __('admin.contracts.container_in-stock'),
					'for-rent'		=> __('admin.contracts.container_for-rent'),
					'to-buy'		=> __('admin.contracts.container_to-buy')
				])
				->rules('required');
		
		$form->radio('volume'		, __('admin.contracts.volume'))
				->options([
					'<1.1'			=> __('admin.contracts.volume-min'),
					'1.1'			=> __('admin.contracts.volume-norm')
				])
				->rules('required');
		
		$form->decimal('count_containers'		, __('admin.contracts.count_containers'))->rules('required');
		
		$form->radio('period'		, __('admin.contracts.period'))
				->options([
					1			=> 1,
					2			=> 2,
					3			=> 3,
					4			=> 4,
					5			=> 5,
					6			=> 6
				])
				->rules('required');
		
		$form->text('address'		, __('admin.contracts.address'))->rules('required|max:150');
		$form->text('name'			, __('admin.contracts.name'))->rules('required|min:2|max:100');
		
		$form->text('phone'			, __('admin.contracts.phone'))->rules('min:9|max:30');
		
		$form->email('email'		, __('admin.contracts.email'))->rules('max:50');
		
		if($this->_edit){
			if(!$this->_update){}
		}
		
		// callback before save
		$form->saving(function (Form $form){
			$form->name			= trim($form->name);
			$form->type			= 'household';
			
			$form->phone		= preg_replace("/[^0-9]/", '', $form->phone);
		});
		
		return $form;
	}
}
