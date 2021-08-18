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
		$grid->column('client'			, __('admin.reviews.client'))->display(function(){
			$client = $this->client;
			
			return '#'.$client->id.' - '.$client->name;
		});
		$grid->column('rating'			, __('admin.reviews.rating'));
		$grid->column('text'			, __('admin.reviews.text'));
		
		$model = $grid->model();
		
		$grid->actions(function($actions){
			//$tools->disableDelete();
			$actions->disableView();
			//$tools->disableList();
		});
		
		$model->orderBy('created_at', 'desc');
		
		$grid->disableCreateButton();
		
		$grid->paginate(100);
		
		return $grid;
	}
	
	protected function detail($id){
		header('Location: /reviews/'.$id.'/edit');
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
		
		$form->radio('rating'			, __('admin.reviews.rating'))
					->options([
						'1'			=> '1',
						'2'			=> '2',
						'3'			=> '3',
						'4'			=> '4',
						'5'			=> '5',
					]);
		
		$form->textarea('text'			, __('admin.reviews.text'))->rules('min:4|max:1500|required');
		
		return $form;
	}
}
