<?php

namespace App\Admin\Controllers;

use App\Models\Acts;
use App\Models\User;

use App\Admin\Controllers\MyAdminController;

use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

use Encore\Admin\Layout\Content;

use App\Helpers\StringHelper;

use DB;

class ActsController extends MyAdminController {
	
	/**
	* Title for current resource.
	*
	* @var string
	*/
	protected $title = 'Акти';
	
	/**
	* Make a grid builder.
	*
	* @return Grid
	*/
	protected function grid(){
		$grid = new Grid(new Acts());
		
		$grid->column('id'				, __('ID'));
		
		$grid->column('created_at'		, __('admin.acts.created_at'));
		$grid->column('date'			, __('admin.acts.date'));
		
		$grid->column('status'			, __('admin.acts.status.label'))->display(function($status){
			$status = (int)$status;
			
			return trans('admin.acts.status.'.$status);
		});
		
		$grid->column('number'			, __('admin.acts.number'));
		
		$grid->column('client_id'		, __('admin.acts.client'))->display(function(){
			$client = $this->client;
			
			if($client){
				return '#'.$client->id.' - '.$client->surname.' '.$client->name;
			}
			
			return '-';
		});
		
		$grid->column('name'			, __('admin.acts.name'));
		
		$model = $grid->model();
		
		$model->orderBy('created_at', 'desc');
		
		$grid->actions(function($actions){
			//$tools->disableDelete();
			$actions->disableView();
			//$tools->disableList();
		});
		
		$grid->filter(function($filter){
			$filter->like('number'			, __('admin.acts.number'));
			$filter->like('name'			, __('admin.acts.name'));
		});
		
		//$grid->disableCreateButton();
		
		$grid->paginate(100);
		
		return $grid;
	}
	
	protected function detail($id){
		header('Location: /admin/acts/'.$id.'/edit');
		return;
	}
	
	/**
	* Make a form builder.
	*
	* @return Form
	*/
	protected function form(){
		$form = new Form(new Acts());
		
		$this->configure($form);
		
		$id = $this->_id;
		
		$users = [];
		
		$tmp = User::query()->get();
		
		foreach($tmp as $item){
			$users[$item->id] = '#'.$item->id.' - '.$item->surname.' '.$item->name;
		}
		
		$form->select('client_id'	, __('admin.acts.client'))->options($users)->rules('required');
		
		$form->date('date'			, __('admin.acts.date'));
		
		$form->text('name'			, __('admin.acts.name'))->rules('max:100');
		
		$form->text('number'		, __('admin.acts.number'))->rules('max:30');
		
		$form->radio('status'		, __('admin.acts.status.label'))
									->options([
										'1'	=> __('admin.acts.status.1'),
										'2'	=> __('admin.acts.status.2'),
										'3'	=> __('admin.acts.status.3'),
										'4'	=> __('admin.acts.status.4')
									])->rules('required');
		
		$form->file('file'			, __('admin.acts.doc'))->help('PDF')->removable()->move('acts')->uniqueName()->rules('required');
		$form->hidden('file_name');
		
		// callback before save
		$form->saving(function (Form $form){
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
