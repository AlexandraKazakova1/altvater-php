<?php

namespace App\Admin\Controllers;

use App\Models\News;

use App\Admin\Controllers\MyAdminController;

use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

use App\Helpers\StringHelper;

class NewsController extends MyAdminController {
	
	/**
	 * Title for current resource.
	 *
	 * @var string
	 */
	protected $title = 'Новини';
	
	/**
	 * Make a grid builder.
	 *
	 * @return Grid
	 */
	protected function grid(){
		$grid = new Grid(new News());
		
		$grid->column('id'				, __('ID'));
		
		$grid->column('created_at'		, __('admin.pages.created_at'));
		$grid->column('updated_at'		, __('admin.pages.updated_at'));
		
		$grid->column('public'			, __('admin.pages.public'))->display(function($public){
			$public = (int)$public;
			
			return $public > 0 ? '<i class="fa fa-check" style="color:green;" aria-hidden="true"></i>' : '<i class="fa fa-times" style="color:red;" aria-hidden="true"></i>';
		});
		
		$grid->column('slug'			, __('admin.pages.slug'))->display(function($slug){
			$slug = trim($slug, '/');
			
			return '<a target="_blank" href="'.url('news/'.$slug).'">'.$slug.'</a>';
		});
		
		$grid->column('title'			, __('admin.pages.title'));
		
		$grid->column('robots'			, __('admin.pages.robots.type'))->display(function($robots){
			return $robots == 'index' ? '<i class="fa fa-check" style="color:green;" aria-hidden="true"></i>' : '<i class="fa fa-times" style="color:red;" aria-hidden="true"></i>';
		});
		
		$grid->column('image'			, __('admin.pages.image'))->image();
		
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
		header('Location: /admin/news/'.$id.'/edit');
		return;
	}
	
	/**
	 * Make a form builder.
	 *
	 * @return Form
	 */
	protected function form(){
		$form = new Form(new News());
		
		$this->configure($form);
		
		$form->tab(__('admin.pages.page_info')		, function($form){
			$form->datetime('updated_at', __('admin.pages.updated_at'))->default(date('Y-m-d H:i:s'));
			
			$form->text('title'			, __('admin.pages.title'))->rules('required|min:3|max:150');
			$form->text('slug'			, __('admin.pages.slug'))->rules('max:150');
			
			$form->switch('public'		, __('admin.pages.public'));
			
			$form->image('image'		, __('admin.pages.image'))->help('675x457px')->removable()->move('news-images')->uniqueName();
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
		
		$form->tab(__('admin.pages.page_content')	, function($form){
			$form->textarea('header'			, __('admin.pages.header'));
			
			$form->ckeditor('text'				, __('admin.pages.text'));
		});
		
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
