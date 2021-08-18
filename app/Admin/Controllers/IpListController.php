<?php

namespace App\Admin\Controllers;

use App\Models\IpList;

use App\Admin\Controllers\MyAdminController;

use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class IpListController extends MyAdminController {
	
	/**
	 * Title for current resource.
	 *
	 * @var string
	 */
	protected $title = 'ACL IP';
	
	/**
	 * Make a grid builder.
	 *
	 * @return Grid
	 */
	protected function grid(){
		$grid = new Grid(new IpList());
		
		//$grid->column('id'			, __('ID'));
		
		$grid->column('ip'			, __('admin.ip-list.ip'))->display(function($ip){
			return \long2ip($ip);
		});
		
		$grid->actions(function($actions){
			//$tools->disableDelete();
			$actions->disableView();
			$actions->disableEdit();
			//$tools->disableList();
		});
		
		$grid->paginate(100);
		
		return $grid;
	}
	
	protected function detail($id){
		header('Location: /ip-list/'.$id.'/edit');
		return;
	}
	
	/**
	 * Make a form builder.
	 *
	 * @return Form
	 */
	protected function form(){
		$form = new Form(new IpList());
		
		$this->configure($form);
		
		$form->footer(function($footer){
			$footer->disableEditingCheck();
		});
		
		$form->text('ip'				, __('admin.ip-list.ip'))->rules('required|min:7|max:15');
		
		$form->saving(function (Form $form){
			$form->ip = \ip2long($form->ip);
		});
		
		return $form;
	}
}
