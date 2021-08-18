<?php

namespace App\Admin\Controllers;

use App\Models\Reviews;

use App\Admin\Controllers\MyAdminController;

use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

use Encore\Admin\Layout\Content;

use DB;

class ReviewsController extends MyAdminController {
	
	/**
	 * Title for current resource.
	 *
	 * @var string
	 */
	protected $title = 'Відгуки';

	/**
	 * Make a grid builder.
	 *
	 * @return Grid
	 */
	protected function grid(){
		$grid = new Grid(new Reviews());
		
		$grid->column('id'				, __('ID'));
		
		$grid->column('created_at'		, __('admin.reviews.created_at'));
		
		$grid->column('public'			, __('admin.reviews.public'))->display(function($public){
			$public = (int)$public;
			
			return $public > 0 ? '<i class="fa fa-check" style="color:green;" aria-hidden="true"></i>' : '<i class="fa fa-times" style="color:red;" aria-hidden="true"></i>';
		});
		
		$grid->column('name'			, __('admin.reviews.client'));
		$grid->column('image'			, __('admin.reviews.image'))->image();
		$grid->column('text'			, __('admin.reviews.text'));
		
		$model = $grid->model();
		
		$grid->actions(function($actions){
			//$tools->disableDelete();
			$actions->disableView();
			//$tools->disableList();
		});
		
		$model->orderBy('created_at', 'desc');
		
		//$grid->disableCreateButton();
		
		$grid->paginate(100);
		
		return $grid;
	}
	
	protected function detail($id){
		header('Location: /admin/reviews/'.$id.'/edit');
		return;
	}
	
	/**
	 * Make a form builder.
	 *
	 * @return Form
	 */
	protected function form(){
		$form = new Form(new Reviews());
		
		$this->configure($form);
		
		$id = $this->_id;
		
		$form->switch('public'			, __('admin.reviews.public'));
		
		$form->text('name'				, __('admin.reviews.client'))->rules('required|min:2|max:250');
		
		$form->image('image'			, __('admin.reviews.image'))->removable()->move('reviews-images')->uniqueName();
		
		$form->textarea('text'			, __('admin.reviews.text'))->rules('min:4|max:1500|required');
		
		return $form;
	}
}
