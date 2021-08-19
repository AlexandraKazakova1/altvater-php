<?php

namespace App\Admin\Controllers;

use App\Models\Pages;

use App\Admin\Controllers\MyAdminController;

use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

use App\Helpers\StringHelper;

class PagesController extends MyAdminController {
	
	/**
	 * Title for current resource.
	 *
	 * @var string
	 */
	protected $title = 'Сторінки';
	
	/**
	 * Make a grid builder.
	 *
	 * @return Grid
	 */
	protected function grid(){
		$grid = new Grid(new Pages());
		
		$grid->column('id'				, __('ID'));
		
		$grid->column('created_at'		, __('admin.pages.created_at'));
		$grid->column('updated_at'		, __('admin.pages.updated_at'));
		
		$grid->column('public'			, __('admin.pages.public'))->display(function($public){
			$public = (int)$public;
			
			return $public > 0 ? '<i class="fa fa-check" style="color:green;" aria-hidden="true"></i>' : '<i class="fa fa-times" style="color:red;" aria-hidden="true"></i>';
		});
		
		$grid->column('slug'			, __('admin.pages.slug'))->display(function($slug){
			$slug = trim($slug, '/');
			
			return '<a target="_blank" href="'.url($slug).'">'.$slug.'</a>';
		});
		
		$grid->column('title'			, __('admin.pages.title'));
		
		$grid->column('robots'			, __('admin.pages.robots.type'))->display(function($robots){
			return $robots == 'index' ? '<i class="fa fa-check" style="color:green;" aria-hidden="true"></i>' : '<i class="fa fa-times" style="color:red;" aria-hidden="true"></i>';
		});
		
		$grid->actions(function($actions){
			//$tools->disableDelete();
			$actions->disableView();
			//$tools->disableList();
		});
		
		$grid->paginate(100);
		
		$grid->filter(function($filter){
			$filter->like('uri'				, __('admin.pages.uri'));
			$filter->like('title'			, __('admin.pages.title'));
		});
		
		return $grid;
	}
	
	protected function detail($id){
		header('Location: /admin/pages/'.$id.'/edit');
		return;
	}
	
	/**
	 * Make a form builder.
	 *
	 * @return Form
	 */
	protected function form(){
		$form = new Form(new Pages());
		
		$this->configure($form);
		
		$id = (int)request()->segment(3);
		
		$form->tab(__('admin.pages.page_info')		, function($form){
			$form->datetime('updated_at', __('admin.pages.updated_at'))->default(date('Y-m-d H:i:s'));
			
			$form->text('title'			, __('admin.pages.title'))->rules('required|min:3|max:150');
			$form->text('slug'			, __('admin.pages.slug'))->rules('max:150');
			
			$form->switch('public'		, __('admin.pages.public'));
		});
		
		$form->tab(__('admin.pages.page_seo')		, function($form){
			$form->text('keywords'		, __('admin.pages.meta_keywords'))->rules('max:150');
			$form->text('description'	, __('admin.pages.meta_description'))->rules('max:150');
			
			$form->text('canonical'		, __('admin.pages.canonical'))->rules('max:150');
			
			$form->radio('robots'		, __('admin.pages.robots.type'))
				->options([
					'index'			=> __('admin.pages.robots.index'),
					'noindex'		=> __('admin.pages.robots.noindex')
				])
				->default('index')
				->rules('required');
		});
		
		if($id == 1){
			$form->tab(__('admin.pages.page_header'), function($form) use ($id) {
				$form->textarea('header'		, __('admin.pages.header'))->rules('max:250');
				$form->textarea('subheader'		, __('admin.pages.subheader'))->rules('max:250');
				
				$form->switch('show_btn'		, __('admin.pages.show_btn'));
				
				$form->text('btn_label'			, __('admin.pages.btn_label'))->rules('max:150');
				$form->text('btn_url'			, __('admin.pages.btn_url'))->rules('max:150');
				$form->text('btn_class'			, __('admin.pages.btn_class'))->rules('max:100');
			});
			
			$form->tab(__('admin.pages.page_about')	, function($form) use ($id) {
				$form->text('about_header'		, __('admin.pages.about_header'))->rules('max:250');
				
				$form->textarea('about_left'	, __('admin.pages.about_left'))->rules('max:1500');
				$form->textarea('about_right'	, __('admin.pages.about_right'))->rules('max:1500');
				
				$form->switch('meta_public'		, __('admin.pages.meta_public'));
				
				$form->text('meta_header'		, __('admin.pages.meta_header'))->rules('max:250');
				$form->textarea('meta_text'		, __('admin.pages.meta_text'))->rules('max:1500');
				
				$form->image('meta_image'		, __('admin.pages.meta_image'))->help('1168x542px')->removable()->move('pages-images')->uniqueName();
			});
		}else{
			$form->tab(__('admin.pages.page_content')	, function($form) use ($id) {
				$form->text('header'			, __('admin.pages.header'))->rules('max:250');
				
				if($id != 3){
					$form->ckeditor('text'				, __('admin.pages.text'));
				}
			});
		}
		
		if($id == 3){
			$form->tab(__('admin.pages.first_block')	, function($form) use ($id) {
				$form->switch('about_right_public'		, __('admin.pages.public'));
				
				$form->text('about_right_header'		, __('admin.pages.header'))->rules('max:250');
				$form->ckeditor('about_right'			, __('admin.pages.text'))->rules('max:1500');
				
				$form->image('about_right_image'		, __('admin.pages.image'))->help('476x388px')->removable()->move('pages-images')->uniqueName();
			});
			
			$form->tab(__('admin.pages.second_block')	, function($form) use ($id) {
				$form->switch('about_left_public'		, __('admin.pages.public'));
				
				$form->text('about_left_header'			, __('admin.pages.header'))->rules('max:250');
				$form->ckeditor('about_left'			, __('admin.pages.text'))->rules('max:1500');
				
				$form->image('about_left_image'			, __('admin.pages.image'))->help('476x388px')->removable()->move('pages-images')->uniqueName();
			});
			
			$form->tab(__('admin.pages.indicators')		, function($form) use ($id) {
				$form->switch('indicators_public'		, __('admin.pages.public'));
				
				$form->decimal('branches'				, __('admin.pages.branches'));
				$form->decimal('orders'					, __('admin.pages.orders'));
				$form->decimal('employees'				, __('admin.pages.employees'));
				$form->decimal('hours'					, __('admin.pages.hours'));
				
				$form->ckeditor('indicators_text'		, __('admin.pages.text'));
			});
			
			$form->tab(__('admin.pages.third_block')	, function($form) use ($id) {
				$form->switch('meta_public'				, __('admin.pages.public'));
				
				$form->text('meta_header'				, __('admin.pages.header'))->rules('max:250');
				$form->ckeditor('meta_text'				, __('admin.pages.text'))->rules('max:1500');
				
				$form->image('meta_image'				, __('admin.pages.image'))->help('476x388px')->removable()->move('pages-images')->uniqueName();
			});
		}
		
		// callback after form submission
		//$form->submitted(function(Form $form){});
		
		// callback before save
		$form->saving(function(Form $form){
			$form->title	= trim($form->title);
			$form->slug		= trim($form->slug);
			
			if(!$form->slug){
				$form->slug = StringHelper::url_title($form->title, 'dash');
			}
		});
		
		// callback after save
		$form->saved(function(Form $form){});
		
		return $form;
	}
}
