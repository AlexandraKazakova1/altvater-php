<?php

namespace App\Http\Controllers\Ajax;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;

use Illuminate\Support\Facades\Validator;

use App\Models\Feedback;

use App\Helpers\StringHelper;
use App\Helpers\ImageHelper;

class SendController extends Controller {
	
	function contact(Request $request){
		$status = false;
		$errors = array();
		$msg	= trans('ajax.failed_send_feedback');
		
		$post = $request->all();
		
		$validator = Validator::make(
			$post,
			array(
				'username'				=> 'required|min:2|max:50',
				'useremail'				=> 'required|email',
				'message'				=> 'max:500',
			),
			array(
				'username.required'		=> trans('ajax_validation.enter_your_name'),
				'username.min'			=> trans('ajax_validation.min_length'),
				'username.max'			=> trans('ajax_validation.max_length'),
				
				'useremail.required'	=> trans('ajax_validation.email_required'),
				'useremail.email'		=> trans('ajax_validation.email_invalid'),
				
				'message.required'		=> trans('ajax_validation.required'),
				'message.min'			=> trans('ajax_validation.min_length'),
				'message.max'			=> trans('ajax_validation.max_length'),
			)
		);
		
		if($validator->passes()){
			$error = false;
			
			if(!$error){
				$insert = Feedback::create([
					'name'		=> $post['username'],
					'email'		=> $post['useremail'],
					'message'	=> $post['message']
				]);
				
				try {
					$this->sendEmail('feedback', null, [
						'id'		=> $insert->id,
						'name'		=> $post['username'],
						'email'		=> $post['useremail'],
						'message'	=> $post['message'],
						'url'		=> url('/admin/feedback/'.$insert->id.'/edit')
					]);
				} catch (Exception $e) {
				}
				
				$status = true;
				$msg	= trans('ajax.success_send_feedback');
			}
		}else{
			$messages = $validator->messages();
			
			foreach($post as $k => $v){
				$error = $messages->first($k);
				
				if($error){
					$errors[$k] = $error;
				}
			}
		}
		
		return array(
			'status'    => $status,
			'msg'       => $msg,
			'errors'    => $errors
		);
	}
}
