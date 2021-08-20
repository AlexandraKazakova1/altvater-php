<?php

namespace App\Admin\Controllers;

use App\Models\Applications;

use App\Admin\Controllers\MyAdminController;

use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

use Encore\Admin\Layout\Content;

use App\Helpers\StringHelper;

use DB;

class ApplicationsController extends MyAdminController {
	
	/**
	* Title for current resource.
	*
	* @var string
	*/
	protected $title = 'Заявки';
	
	/**
	* Make a grid builder.
	*
	* @return Grid
	*/
	protected function grid(){
		$grid = new Grid(new Applications());
		
		$grid->column('id'				, __('ID'));
		
		$grid->column('created_at'		, __('admin.applications.created_at'));
		
		$grid->column('name'			, __('admin.applications.name'));
		
		$grid->column('type'			, __('admin.applications.type'))->display(function($type){
			if($type){
				return __('admin.applications.type_'.$type);
			}
			
			return '-';
		});
		
		$grid->column('address'			, __('admin.applications.address'));
		
		$model = $grid->model();
		
		$model->orderBy('created_at', 'desc');
		
		$grid->actions(function($actions){
			//$tools->disableDelete();
			$actions->disableView();
			//$tools->disableList();
		});
		
		$grid->filter(function($filter){
			$filter->like('name'			, __('admin.applications.name'));
			$filter->like('address'			, __('admin.applications.address'));
		});
		
		$grid->disableCreateButton();
		
		$grid->paginate(100);
		
		return $grid;
	}
	
	protected function detail($id){
		header('Location: /applications/'.$id.'/edit');
		return;
	}
	
	/**
	* Make a form builder.
	*
	* @return Form
	*/
	protected function form(){
		$form = new Form(new Applications());
		
		$this->configure($form);
		
		$id = $this->_id;
		
		$form->tab(__('admin.applications.info')		, function($form) use ($id){
			$form->radio('type'			, __('admin.applications.type'))
					->options([
						'household'		=> __('admin.applications.type_household'),
						'separate'		=> __('admin.applications.type_separate'),
						'construction'	=> __('admin.applications.type_construction')
					])
					->rules('required');
			
			$form->radio('service'		, __('admin.applications.service'))
					->options([
						null			=> '-',
						'install'		=> __('admin.applications.service_install'),
						'replace'		=> __('admin.applications.service_replace'),
						'withdraw'		=> __('admin.applications.service_withdraw')
					]);
			
			$form->radio('volume'		, __('admin.applications.volume'))
					->options([
						null			=> '-',
						'8-11'			=> '8-11',
						'15-17'			=> '15-17',
						'23-25'			=> '23-25',
						'30-35'			=> '30-35',
					]);
			
			$form->text('address'		, __('admin.applications.address'))->rules('required|max:150');
			$form->text('name'			, __('admin.applications.name'))->rules('required|min:2|max:100');
			
			$form->text('phone'			, __('admin.applications.phone'))->rules('min:9|max:30');
			
			$form->email('email'		, __('admin.applications.email'))->rules('max:50');
		});
		
		$form->tab(__('admin.applications.photos')		, function($form) use ($id){
			$form->hasMany('photos', '', function($form){
				$form->image('image'			, __('admin.applications.image'))->removable()->move('applications-images')->uniqueName();
			});
		});
		
		if($this->_edit){
			if(!$this->_update){}
		}
		
		// callback before save
		$form->saving(function (Form $form){
			$form->name			= trim($form->name);
			
			$form->phone		= preg_replace("/[^0-9]/", '', $form->phone);
		});
		
		return $form;
	}
}
