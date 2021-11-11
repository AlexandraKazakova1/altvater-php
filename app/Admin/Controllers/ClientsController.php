<?php

namespace App\Admin\Controllers;

use App\Models\User;

use App\Admin\Controllers\MyAdminController;

use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

use Encore\Admin\Layout\Content;

use App\Helpers\StringHelper;

use DB;

class ClientsController extends MyAdminController {
	
	/**
	* Title for current resource.
	*
	* @var string
	*/
	protected $title = 'Клієнти';
	
	/**
	* Make a grid builder.
	*
	* @return Grid
	*/
	protected function grid(){
		$grid = new Grid(new User());
		
		$grid->column('id'				, __('ID'));
		
		$grid->column('created_at'		, __('admin.clients.created_at'));
		
		$grid->column('name'			, __('admin.clients.name'));
		$grid->column('surname'			, __('admin.clients.surname'));
		
		$grid->column('phone'			, __('admin.clients.phone'));
		
		$grid->column('email'			, __('admin.clients.email'));
		
		$grid->column('blocked'			, __('admin.clients.blocked'))->display(function($blocked){
			$blocked = (int)$blocked;
			
			return $blocked > 0 ? '<i class="fa fa-check" style="color:green;" aria-hidden="true"></i>' : '<i class="fa fa-times" style="color:red;" aria-hidden="true"></i>';
		});
		
		$model = $grid->model();
		
		$model->orderBy('created_at', 'desc');
		
		$grid->actions(function($actions){
			//$tools->disableDelete();
			$actions->disableView();
			//$tools->disableList();
		});
		
		$grid->filter(function($filter){
			$filter->like('name'			, __('admin.clients.name'));
			$filter->like('surname'			, __('admin.clients.surname'));
			$filter->like('middlename'		, __('admin.clients.patronymic'));
			
			$filter->like('phone'			, __('admin.clients.phone'));
			$filter->like('email'			, __('admin.clients.email'));
		});
		
		//$grid->disableCreateButton();
		
		$grid->paginate(100);
		
		return $grid;
	}
	
	protected function detail($id){
		header('Location: /admin/clients/'.$id.'/edit');
		return;
	}
	
	/**
	* Make a form builder.
	*
	* @return Form
	*/
	protected function form(){
		$form = new Form(new User());
		
		$this->configure($form);
		
		$id = $this->_id;
		
		$form->tab(__('admin.clients.info')		, function($form) use ($id){
			$form->text('name'				, __('admin.clients.name'))->rules('required|min:2|max:100');
			$form->text('surname'			, __('admin.clients.surname'))->rules('max:50');
			$form->text('middlename'		, __('admin.clients.patronymic'))->rules('max:50');
			
			$form->text('company_name'		, __('admin.clients.company_name'))->rules('max:100');
			
			$form->email('email'			, __('admin.clients.email'))->rules('required|email');
			$form->switch('verify_email'	, __('admin.clients.verify_email'));
			
			$form->text('phone'				, __('admin.clients.phone'))->rules('required|min:12|max:12');
			$form->switch('verify_phone'	, __('admin.clients.verify_phone'));
			
			$form->text('extra_phone'		, __('admin.clients.extra_phone'))->rules('max:12');
			
			$form->switch('blocked'			, __('admin.clients.blocked'));
			
			$form->radio('type'				, __('admin.clients.type.label'))
						->options([
							null			=> '-',
							'individual'	=> __('admin.clients.type.individual'),
							'legal-entit'	=> __('admin.clients.type.legal-entit')
						]);
			
			$form->password('password'		, __('admin.clients.password'));
			
			$form->text('position'			, __('admin.clients.position'))->rules('max:50');
			$form->text('addresses'			, __('admin.clients.address'))->rules('max:150');
			$form->text('index'				, __('admin.clients.index'))->rules('max:10');
			
			$form->text('ipn'				, __('admin.clients.ipn'))->rules('max:15');
			$form->text('uedrpou'			, __('admin.clients.uedrpou'))->rules('max:50');
		});
		
		$form->tab(__('admin.clients.addresses')		, function($form) use ($id){
			$form->hasMany('user_addresses', '', function($form){
				$form->text('name'			, __('admin.addresses.name'))->rules('required|max:150');
				$form->text('address'		, __('admin.addresses.address'))->rules('required|max:200');
				
				//$form->image('image'			, __('admin.applications.image'))->removable()->move('applications-images')->uniqueName();
			});
		});
		
		// callback before save
		$form->saving(function (Form $form){
			$form->name			= trim($form->name);
			
			if($form->password && $form->model()->password != $form->password){
				$form->password = bcrypt($form->password);
			}
		});
		
		$form->tools(function(Form\Tools $tools){
			$tools->disableView();
		});
		
		$form->footer(function($footer){
			$footer->disableReset();
			$footer->disableViewCheck();
			//$footer->disableCreatingCheck();
		});
		
		return $form;
	}
}
