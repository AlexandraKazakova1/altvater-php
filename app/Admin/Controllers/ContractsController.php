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

class ContractsController extends MyAdminController {
	
	/**
	* Title for current resource.
	*
	* @var string
	*/
	protected $title = 'Договори';
	
	/**
	* Make a grid builder.
	*
	* @return Grid
	*/
	protected function grid(){
		$grid = new Grid(new Contracts());
		
		$grid->column('id'				, __('ID'));
		
		$grid->column('created_at'		, __('admin.contracts.created_at'));
		$grid->column('date'			, __('admin.contracts.date'));
		
		$grid->column('archive'			, __('admin.contracts.archive'))->display(function($archive){
			$archive = (int)$archive;
			
			return $archive > 0 ? '<i class="fa fa-check" style="color:green;" aria-hidden="true"></i>' : '<i class="fa fa-times" style="color:red;" aria-hidden="true"></i>';
		});
		
		$grid->column('name'			, __('admin.contracts.name'));
		$grid->column('contact'			, __('admin.contracts.contact'));
		$grid->column('address'			, __('admin.contracts.address'));
		$grid->column('phone'			, __('admin.contracts.phone'));
		$grid->column('email'			, __('admin.contracts.email'));
		
		$model = $grid->model();
		
		$model->orderBy('created_at', 'desc');
		
		$grid->actions(function($actions){
			//$tools->disableDelete();
			$actions->disableView();
			//$tools->disableList();
		});
		
		$grid->filter(function($filter){
			$filter->like('name'			, __('admin.contracts.name'));
			$filter->like('contact'			, __('admin.contracts.contact'));
			$filter->like('phone'			, __('admin.contracts.phone'));
			$filter->like('email'			, __('admin.contracts.email'));
			$filter->like('address'			, __('admin.contracts.address'));
		});
		
		//$grid->disableCreateButton();
		
		$grid->paginate(100);
		
		return $grid;
	}
	
	protected function detail($id){
		header('Location: /admin/contracts/'.$id.'/edit');
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
		
		$id = (int)request()->segment(3);
		
		$form->text('number'		, __('admin.contracts.number'))->rules('max:30');
		
		$form->text('name'			, __('admin.contracts.name'))->rules('max:100');
		$form->text('contact'		, __('admin.contracts.contact'))->rules('max:100');
		
		$form->text('phone'			, __('admin.contracts.phone'))->rules('min:9|max:30');
		$form->text('extra_phone'	, __('admin.contracts.extra_phone'))->rules('min:9|max:30');
		
		$form->email('email'		, __('admin.contracts.email'))->rules('max:50');
		
		$form->text('address'		, __('admin.contracts.address'))->rules('max:150');
		
		$form->text('index'			, __('admin.contracts.index'))->rules('max:6');
		$form->text('ipn'			, __('admin.contracts.ipn'))->rules('max:15');
		$form->text('edrpou'		, __('admin.contracts.edrpou'))->rules('max:40');
		
		$form->date('date'			, __('admin.contracts.date'));
		
		$form->switch('archive'		, __('admin.contracts.archive'));
		
		$form->hidden('file_name');
		
		$form->file('file'			, __('admin.contracts.doc'))->help('PDF')->removable()->move('contracts')->uniqueName();
		
		// callback before save
		$form->saving(function (Form $form){
			$form->name			= trim($form->name);
			$form->contact		= trim($form->contact);
			
			$form->phone		= preg_replace("/[^0-9]/", '', $form->phone);
			$form->extra_phone	= preg_replace("/[^0-9]/", '', $form->extra_phone);
			
			$form->index		= preg_replace("/[^0-9]/", '', $form->index);
			$form->ipn			= preg_replace("/[^0-9]/", '', $form->ipn);
			$form->edrpou		= preg_replace("/[^0-9]/", '', $form->edrpou);
			
			if(!empty($_FILES['file']['name'])){
				$form->file_name = $_FILES['file']['name'];
			}
		});
		
		$form->saved(function(Form $form){
			$id = $form->model()->id;
		});
		
		return $form;
	}
}
