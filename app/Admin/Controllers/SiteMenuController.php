<?php

namespace App\Admin\Controllers;

use App\Models\SiteMenu;

use App\Admin\Controllers\MyAdminController;

use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

use Encore\Admin\Layout\Content;

use DB;

class SiteMenuController extends MyAdminController {
	
	/**
	 * Title for current resource.
	 *
	 * @var string
	 */
	protected $title = 'Меню сайту';
	
	/**
	 * Make a grid builder.
	 *
	 * @return Grid
	 */
	protected function grid(){
		$grid = new Grid(new SiteMenu());
		
		$grid->column('id'				, __('ID'));
		
		$grid->column('created_at'		, __('admin.site-menu.created_at'));
		
		$grid->column('sort'			, __('admin.site-menu.sort'));
		
		$grid->column('public'			, __('admin.site-menu.public'))->display(function($public){
			$public = (int)$public;
			
			return $public > 0 ? '<i class="fa fa-check" style="color:green;" aria-hidden="true"></i>' : '<i class="fa fa-times" style="color:red;" aria-hidden="true"></i>';
		});
		
		$grid->column('url'				, __('admin.site-menu.url'));
		$grid->column('title'			, __('admin.site-menu.title'));
		
		$model = $grid->model();
		
		$grid->actions(function($actions){
			//$tools->disableDelete();
			$actions->disableView();
			//$tools->disableList();
		});
		
		$model->orderBy('sort', 'asc');
		
		//$grid->disableCreateButton();
		
		$grid->paginate(100);
		
		return $grid;
	}
	
	protected function detail($id){
		header('Location: /admin/site-menu/'.$id.'/edit');
		return;
	}
	
	/**
	 * Make a form builder.
	 *
	 * @return Form
	 */
	protected function form(){
		$form = new Form(new SiteMenu());
		
		$this->configure($form);
		
		$id = $this->_id;
		
		$form->switch('public'			, __('admin.site-menu.public'));
		
		$form->decimal('sort'			, __('admin.site-menu.sort'));
		
		$form->text('url'				, __('admin.site-menu.url'))->rules('min:1|max:100|required');
		$form->text('title'				, __('admin.site-menu.title'))->rules('min:1|max:100|required');
		$form->text('class'				, __('admin.site-menu.class'))->rules('max:50');
		
		$form->saving(function(Form $form){
			$form->sort			= (int)trim($form->sort);
			
			if($form->sort < 1){
				$count = DB::table('menu')->count();
				$count++;
				
				$form->sort = $count;
			}
		});
		
		return $form;
	}
}
