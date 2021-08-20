<?php

namespace App\Admin\Controllers;

use App\Models\FAQ;

use App\Admin\Controllers\MyAdminController;

use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

use Encore\Admin\Layout\Content;

use DB;

class FAQController extends MyAdminController {
	
	/**
	 * Title for current resource.
	 *
	 * @var string
	 */
	protected $title = 'FAQ';
	
	/**
	 * Make a grid builder.
	 *
	 * @return Grid
	 */
	protected function grid(){
		$grid = new Grid(new FAQ());
		
		$grid->column('id'				, __('ID'));
		
		$grid->column('created_at'		, __('admin.faq.created_at'));
		
		$grid->column('sort'			, __('admin.faq.sort'));
		
		$grid->column('public'			, __('admin.faq.public'))->display(function($public){
			$public = (int)$public;
			
			return $public > 0 ? '<i class="fa fa-check" style="color:green;" aria-hidden="true"></i>' : '<i class="fa fa-times" style="color:red;" aria-hidden="true"></i>';
		});
		
		$grid->column('title'			, __('admin.faq.title'));
		
		$model = $grid->model();
		
		$grid->actions(function($actions){
			//$tools->disableDelete();
			$actions->disableView();
			//$tools->disableList();
		});
		
		$model->orderBy('sort', 'desc');
		
		//$grid->disableCreateButton();
		
		$grid->paginate(100);
		
		return $grid;
	}
	
	protected function detail($id){
		header('Location: /admin/faq/'.$id.'/edit');
		return;
	}
	
	/**
	 * Make a form builder.
	 *
	 * @return Form
	 */
	protected function form(){
		$form = new Form(new FAQ());
		
		$this->configure($form);
		
		$id = $this->_id;
		
		$form->switch('public'			, __('admin.faq.public'));
		
		$form->decimal('sort'			, __('admin.faq.sort'));
		
		$form->textarea('title'			, __('admin.faq.title'))->rules('min:2|max:250|required');
		
		$form->textarea('text'			, __('admin.faq.text'))->rules('min:4|max:1500|required');
		
		$form->saving(function(Form $form){
			$form->sort			= (int)trim($form->sort);
			
			if($form->sort < 1){
				$count = DB::table('faq')->count();
				$count++;
				
				$form->sort = $count;
			}
		});
		
		return $form;
	}
}
