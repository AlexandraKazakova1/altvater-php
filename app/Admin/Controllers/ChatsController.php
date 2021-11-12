<?php

namespace App\Admin\Controllers;

use App\Models\User;
use App\Models\Dialogues;
use App\Models\Messages;

use App\Models\Contracts;
use App\Models\Themes;

use App\Admin\Controllers\MyAdminController;

use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

use Encore\Admin\Layout\Content;

use App\Helpers\StringHelper;

use DB;

class ChatsController extends MyAdminController {
	
	/**
	* Title for current resource.
	*
	* @var string
	*/
	protected $title = 'Чати';
	
	/**
	* Make a grid builder.
	*
	* @return Grid
	*/
	protected function grid(){
		$grid = new Grid(new Dialogues());
		
		$grid->column('id'				, __('ID'));
		
		$grid->column('created_at'		, __('admin.chats.created_at'));
		
		$grid->column('client_id'		, __('admin.chats.client'))->display(function(){
			$client = $this->client;
			
			if($client){
				return '#'.$client->id.' - '.$client->surname.' '.$client->name;
			}
			
			return '-';
		});
		
		$grid->column('theme_id'		, __('admin.chats.theme'))->display(function(){
			$theme = $this->theme;
			
			if($theme){
				return $theme->label;
			}
			
			return '-';
		});
		
		$grid->column('contract_id'		, __('admin.chats.contract'))->display(function(){
			$contract = $this->contract;
			
			if($contract){
				return '#'.$contract->number;
			}
			
			return '-';
		});
		
		$grid->column('header'			, __('admin.chats.header'));
		$grid->column('phone'			, __('admin.chats.phone'));
		
		$model = $grid->model();
		
		$model->orderBy('created_at', 'desc');
		
		$grid->actions(function($actions){
			//$tools->disableDelete();
			$actions->disableView();
			//$tools->disableList();
		});
		
		$grid->filter(function($filter){
			$filter->like('phone'			, __('admin.chats.phone'));
			//$filter->like('theme'			, __('admin.chats.name'));
		});
		
		$grid->disableCreateButton();
		
		$grid->paginate(100);
		
		return $grid;
	}
	
	protected function detail($id){
		header('Location: /admin/chats/'.$id.'/edit');
		return;
	}
	
	/**
	* Make a form builder.
	*
	* @return Form
	*/
	protected function form(){
		$form = new Form(new Dialogues());
		
		$this->configure($form);
		
		$id = (int)request()->segment(3);
		
		$form->tab(__('admin.chats.info')		, function($form) use ($id) {
			$users = [];
			
			$tmp = User::query()->get();
			
			foreach($tmp as $item){
				$users[$item->id] = '#'.$item->id.' - '.$item->surname.' '.$item->name;
			}
			
			$form->select('client_id'	, __('admin.chats.client'))->options($users)->rules('required');
			
			//
			
			$themes = [];
			
			$tmp = Themes::query()->get();
			
			foreach($tmp as $item){
				$themes[$item->id] = $item->label;
			}
			
			$form->select('theme_id'	, __('admin.chats.theme'))->options($themes)->rules('required');
			
			//
			
			$contracts = [];
			
			$tmp = Contracts::query()->get();
			
			foreach($tmp as $item){
				$contracts[$item->id] = $item->number;
			}
			
			$form->select('contract_id'	, __('admin.chats.contract'))->options($contracts);
			
			//
			
			$form->text('header'		, __('admin.chats.header'))->rules('max:150|required');
			$form->text('phone'			, __('admin.chats.phone'))->rules('max:15');
			
			$form->file('file'			, __('admin.chats.file'))->help('PDF, JPEG')->removable()->move('chats')->uniqueName();
		});
		
		$form->tab(__('admin.chats.messages')		, function($form) use ($id) {
			$form->hasMany('messages', '', function($form){
				$form->textarea('text'		, __('admin.chats.message'))->rules('readonly');
			});
			
			$form->textarea('answer'		, __('admin.chats.answer'))->rules('max:1000');
		});
		
		// callback before save
		$form->saving(function (Form $form){
		});
		
		$form->saved(function(Form $form){
			$id = $form->model()->id;
		});
		
		return $form;
	}
}
