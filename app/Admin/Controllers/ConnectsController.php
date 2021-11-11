<?php

namespace App\Admin\Controllers;

use App\Models\Connects;
use App\Models\User;

use App\Admin\Controllers\MyAdminController;

use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

use Encore\Admin\Layout\Content;

use App\Helpers\StringHelper;

use DB;

class ConnectsController extends MyAdminController {
	
	/**
	* Title for current resource.
	*
	* @var string
	*/
	protected $title = 'Запити на приєднання договорів';
	
	/**
	* Make a grid builder.
	*
	* @return Grid
	*/
	protected function grid(){
		$grid = new Grid(new Connects());
		
		$grid->column('id'					, __('ID'));
		
		$grid->column('created_at'			, __('admin.connects.created_at'));
		
		$grid->column('confirm'				, __('admin.connects.confirm'))->display(function($confirm){
			$confirm = (int)$confirm;
			
			return $confirm > 0 ? '<i class="fa fa-check" style="color:green;" aria-hidden="true"></i>' : '<i class="fa fa-times" style="color:red;" aria-hidden="true"></i>';
		});
		
		$grid->column('client_id'			, __('admin.connects.client'))->display(function(){
			$client = $this->client;
			
			if($client){
				return '#'.$client->id.' - '.$client->surname.' '.$client->name;
			}
			
			return '-';
		});
		
		$grid->column('number'				, __('admin.connects.number'));
		$grid->column('name'				, __('admin.connects.name'));
		$grid->column('edrpou'				, __('admin.connects.edrpou'));
		
		$model = $grid->model();
		
		$model->orderBy('created_at', 'desc');
		
		$grid->actions(function($actions){
			//$tools->disableDelete();
			$actions->disableView();
			//$tools->disableList();
		});
		
		$grid->filter(function($filter){
			$filter->like('number'			, __('admin.connects.number'));
			$filter->like('name'			, __('admin.connects.name'));
			$filter->like('edrpou'			, __('admin.connects.edrpou'));
		});
		
		$grid->disableCreateButton();
		
		$grid->paginate(100);
		
		return $grid;
	}
	
	protected function detail($id){
		header('Location: /admin/connects/'.$id.'/edit');
		return;
	}
	
	/**
	* Make a form builder.
	*
	* @return Form
	*/
	protected function form(){
		$form = new Form(new Connects());
		
		$this->configure($form);
		
		$id = (int)request()->segment(3);
		
		$record = Connects::query()->where('id', $id)->first();
		
		$users = [];
		
		$tmp = User::query()->get();
		
		foreach($tmp as $item){
			$users[$item->id] = '#'.$item->id.' - '.$item->surname.' '.$item->name;
		}
		
		$form->select('client_id'	, __('admin.connects.client'))->options($users);
		
		$form->text('number'		, __('admin.connects.number'))->rules('max:30');
		$form->text('name'			, __('admin.connects.name'))->rules('max:100');
		$form->text('edrpou'		, __('admin.connects.edrpou'))->rules('max:20');
		
		if(!$record->confirm){
			$form->switch('confirm'		, __('admin.connects.confirm'));
		}
		
		// callback before save
		$form->saving(function (Form $form) use ($record) {
			print_r($form->confirm);
			exit;
		});
		
		$form->saved(function(Form $form){
			$id = $form->model()->id;
		});
		
		return $form;
	}
}
