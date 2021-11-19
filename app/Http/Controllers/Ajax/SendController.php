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
use App\Models\Services;

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
				'name'				=> 'required|min:2|max:50',
				'phone'				=> 'required|min:12|max:13',
				//'email'				=> 'required|email',
				'message'			=> 'max:500',
			),
			array(
				'name.required'		=> trans('ajax_validation.enter_your_name'),
				'name.min'			=> trans('ajax_validation.min_length'),
				'name.max'			=> trans('ajax_validation.max_length'),
				
				'email.required'	=> trans('ajax_validation.email_required'),
				'email.email'		=> trans('ajax_validation.email_invalid'),
				
				'message.required'		=> trans('ajax_validation.required'),
				'message.min'			=> trans('ajax_validation.min_length'),
				'message.max'			=> trans('ajax_validation.max_length'),
				
				'phone.required'		=> trans('ajax_validation.required'),
				'phone.min'			=> trans('ajax_validation.min_length'),
				'phone.max'			=> trans('ajax_validation.max_length'),
			)
		);
		
		if($validator->passes()){
			$error = false;
			
			if(!$error){
				$post['phone'] = preg_replace("/[^0-9]/", '', $post['phone']);
				
				$insert = Feedback::create([
					'name'		=> $post['name'],
					//'email'		=> $post['email'],
					'phone'		=> $post['phone'],
					'message'	=> $post['message']
				]);
				
				try {
					$this->sendEmail('feedback', null, [
						'id'		=> $insert->id,
						'name'		=> $post['name'],
						'email'		=> $insert->email,
						'email'		=> $insert->phone,
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
	
	function service(Request $request){
		$status = false;
		$errors = array();
		$msg	= trans('ajax.failed_service');
		
		$post = $request->all();
		
		$validator = Validator::make(
			$post,
			array(
				'service_id'		=> 'required',
				'name'				=> 'required|min:2|max:100',
				'phone'				=> 'required|min:19|max:19',
				'message'			=> 'max:500',
			),
			array(
				'service_id.required'	=> trans('ajax_validation.required'),
				
				'name.required'			=> trans('ajax_validation.required'),
				'name.min'				=> trans('ajax_validation.min_length'),
				'name.max'				=> trans('ajax_validation.max_length'),
				
				'phone.required'		=> trans('ajax_validation.required'),
				'phone.min'				=> trans('ajax_validation.min_length'),
				'phone.max'				=> trans('ajax_validation.max_length'),
				
				'message.required'		=> trans('ajax_validation.required'),
				'message.min'			=> trans('ajax_validation.min_length'),
				'message.max'			=> trans('ajax_validation.max_length')
			)
		);
		
		if($validator->passes()){
			$error = false;
			
			if(!$error){
				$service_id	= (int)$request->get('service_id');
				
				if(!$service_id){
					$error = true;
				}else{
					$service = Services::query()->where('id', $service_id)->first();
					
					if(!$service){
						$error = true;
					}
				}
			}
			
			if(!$error){
				$post['phone'] = preg_replace("/[^0-9]/", '', $post['phone']);
				
				try {
					$this->sendEmail('service', null, [
						'service'	=> $service->title,
						'name'		=> $post['name'],
						'phone'		=> $post['phone'],
						'message'	=> $post['message']
					]);
					
					$status = true;
					$msg	= trans('ajax.success_service');
				} catch (Exception $e) {
				}
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
