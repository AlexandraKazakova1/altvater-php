<?php

namespace App\Admin\Controllers;

use App\Models\Bills;
use App\Models\User;

use App\Admin\Controllers\MyAdminController;

use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

use Encore\Admin\Layout\Content;

use App\Helpers\StringHelper;

use DB;

class BillsController extends MyAdminController {
	
	/**
	* Title for current resource.
	*
	* @var string
	*/
	protected $title = 'Рахунки';
	
	/**
	* Make a grid builder.
	*
	* @return Grid
	*/
	protected function grid(){
		$grid = new Grid(new Bills());
		
		$grid->column('id'				, __('ID'));
		
		$grid->column('created_at'		, __('admin.bills.created_at'));
		$grid->column('date'			, __('admin.bills.date'));
		
		$grid->column('paid'			, __('admin.bills.paid'))->display(function($paid){
			$paid = (int)$paid;
			
			return $paid > 0 ? '<i class="fa fa-check" style="color:green;" aria-hidden="true"></i>' : '<i class="fa fa-times" style="color:red;" aria-hidden="true"></i>';
		});
		
		$grid->column('number'			, __('admin.bills.number'));
		
		$grid->column('amount'			, __('admin.bills.amount'));
		
		$grid->column('client_id'		, __('admin.bills.client'))->display(function(){
			$client = $this->client;
			
			if($client){
				return '#'.$client->id.' - '.$client->surname.' '.$client->name;
			}
			
			return '-';
		});
		
		$grid->column('name'			, __('admin.bills.name'));
		
		$model = $grid->model();
		
		$model->orderBy('created_at', 'desc');
		
		$grid->actions(function($actions){
			//$tools->disableDelete();
			$actions->disableView();
			//$tools->disableList();
		});
		
		$grid->filter(function($filter){
			$filter->like('number'			, __('admin.bills.number'));
			$filter->like('name'			, __('admin.bills.name'));
		});
		
		//$grid->disableCreateButton();
		
		$grid->paginate(100);
		
		return $grid;
	}
	
	protected function detail($id){
		header('Location: /admin/bills/'.$id.'/edit');
		return;
	}
	
	/**
	* Make a form builder.
	*
	* @return Form
	*/
	protected function form(){
		$form = new Form(new Bills());
		
		$this->configure($form);
		
		$id = $this->_id;
		
		$users = [];
		
		$tmp = User::query()->get();
		
		foreach($tmp as $item){
			$users[$item->id] = '#'.$item->id.' - '.$item->surname.' '.$item->name;
		}
		
		$form->select('client_id'	, __('admin.bills.client'))->options($users);
		
		$form->date('date'			, __('admin.bills.date'));
		
		$form->text('name'			, __('admin.bills.name'))->rules('max:100');
		
		$form->text('number'		, __('admin.bills.number'))->rules('max:15');
		$form->text('amount'		, __('admin.bills.amount'))->rules('max:15');
		
		$form->switch('paid'		, __('admin.bills.paid'));
		
		$form->file('file'			, __('admin.bills.doc'))->help('PDF')->removable()->move('bills')->uniqueName();
		
		// callback before save
		$form->saving(function (Form $form){
			$form->number		= trim($form->number);
			$form->amount		= trim($form->amount);
			
			$form->number		= preg_replace("/[^0-9]/", '', $form->number);
			$form->amount		= preg_replace("/[^0-9\.,]/", '', $form->amount);
		});
		
		$form->saved(function(Form $form){
			$id = $form->model()->id;
		});
		
		return $form;
	}
}
