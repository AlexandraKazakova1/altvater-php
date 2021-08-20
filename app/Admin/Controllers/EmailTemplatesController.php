<?php

namespace App\Admin\Controllers;

use App\Models\EmailTemplates;

use App\Admin\Controllers\MyAdminController;

use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class EmailTemplatesController extends MyAdminController{
	
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Шаблони листів';
    
    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid(){
        $grid = new Grid(new EmailTemplates());
        
        $grid->column('id', __('ID'));
        
        $grid->column('name', __('admin.email.name'));
        //$grid->column('slug', __('admin.email.slug'));
        
        $grid->column('subject', __('admin.email.subject'));
        
        //$grid->column('content', __('Content'));
        //$grid->column('description', __('Description'));
        
        //$grid->column('from_name', __('admin.email.from_name'));
        //$grid->column('from_email', __('admin.email.from_email'));
        
        $grid->column('emails', __('admin.email.emails'));
        
        //$grid->column('created_at', __('Created at'));
        //$grid->column('updated_at', __('Updated at'));
        //$grid->column('deleted_at', __('Deleted at'));
		
		$grid->actions(function($actions){
			//$tools->disableDelete();
			$actions->disableView();
			//$tools->disableList();
		});
		
		$grid->paginate(100);
		
		return $grid;
	}
	
    /**
     * Make a show builder.
     *
     * @param mixed $id
     * @return Show
     */
    protected function detail($id){
        $show = new Show(EmailTemplates::findOrFail($id));
        
        $show->field('id', __('Id'));
        
        $show->field('name', __('admin.email.name'));
        $show->field('slug', __('admin.email.slug'));
        
        $show->field('subject', __('admin.email.subject'));
        
        $show->field('description', __('admin.email.description'));
        
        $show->field('from_name', __('admin.email.from_name'));
        $show->field('from_email', __('admin.email.from_email'));
        
        $show->field('emails', __('admin.email.emails'));
        
        $show->field('content', __('admin.email.content'));
        
        //$show->field('created_at', __('Created at'));
        //$show->field('updated_at', __('Updated at'));
        //$show->field('deleted_at', __('Deleted at'));
        
        return $show;
    }
    
    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form(){
        $form = new Form(new EmailTemplates());
		
        $this->configure($form);
        
        $form->text('name', __('admin.email.name'))->rules('min:2|max:255|required');
        $form->text('slug', __('admin.email.slug'))->rules('min:2|max:255|required');
        
        $form->text('subject', __('admin.email.subject'))->rules('min:2|max:255|required');
        
        $form->text('description', __('admin.email.description'));
        
        $form->text('from_name', __('admin.email.from_name'))->rules('min:2|max:255|required');
        $form->text('from_email', __('admin.email.from_email'))->rules('min:2|max:255|required');
        
        $form->text('emails', __('admin.email.emails'))->rules('max:250')->help(trans('admin.email.through-whom'));
        
        $form->summernote('content', __('admin.email.content'));
        
        return $form;
    }
}
