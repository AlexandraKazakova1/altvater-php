<?php

namespace App\Http\Controllers\Ajax;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

use App\Helpers\StringHelper;

use App\Models\User;
use App\Models\Orders;
use App\Models\OrdersServices;

class OrdersController extends Controller {
	
	public $_auth	= false;
	public $_user	= [];
	public $_id		= 0;
	
	function session(){
		$this->_user = Auth::user();
		
		if($this->_user){
			//$this->_user = (array)$this->_user;
			
			$this->_id = $this->_user->id;
			
			$this->_auth = true;
		};
		
		//View::share('user'	, $this->_user);
	}
	
	function add(Request $request){
		$this->session();
		
		$post = $request->all();
		
		$status = false;
		$errors = array();
		$msg	= trans('ajax.failed_add_order');
		$payload= [];
		
		if(!$this->_auth){
			return response()->json([
				'status' 	=> $status,
				'message'	=> $msg,
				'errors'	=> $errors,
				'payload'	=> $payload
			], 200, [], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
		}
		
		$validator = Validator::make(
			$post,
			array(
				'service'				=> 'required',
				
				'addresses'				=> 'required|string|min:2|max:200',
				'comment'				=> 'required|string|min:2|max:500',
			),
			array(
				'service.required'		=> trans('ajax_validation.required'),
				
				'addresses.required'	=> trans('ajax_validation.required'),
				'addresses.min'			=> trans('ajax_validation.min_length'),
				'addresses.max'			=> trans('ajax_validation.max_length'),
				
				'comment.required'		=> trans('ajax_validation.required'),
				'comment.min'			=> trans('ajax_validation.min_length'),
				'comment.max'			=> trans('ajax_validation.max_length'),
			)
		);
		
		if($validator->passes()){
			$error	= false;
			
			$post['service']	= (int)$post['service'];
			
			$service = OrdersServices::query()->where('id', $post['service'])->first();
			
			if(!$service){
				$error = true;
			}
			
			if(!$error){
				// 2099-01-31
				
				$pattern_date = '#(19|20)[0-9]{2}[- /.](0[1-9]|1[012])[- /.](0[1-9]|[12][0-9]|3[01])#';
				
				if(!preg_match($pattern_date, $post['date'])){
					$error = true;
				}
			}
			
			if(!$error){
				// 10:10
				
				$pattern_time = '#[0-9]{2}[:][0-9]{2}#';
				
				if(!preg_match($pattern_time, $post['time'])){
					$error = true;
				}
			}
			
			//
			
			if(!$error){
				$post['addresses']	= trim($post['addresses']);
				$post['comment']	= trim($post['comment']);
				
				$record = Orders::create([
					'client_id'			=> $this->_id,
					'service_id'		=> $post['service'],
					'status'			=> 'new',
					'date'				=> $post['date'],
					'time'				=> $post['time'],
					'addresses'			=> $post['addresses'],
					'comment'			=> $post['comment'],
				]);
				
				$status = true;
				$msg	= trans('ajax.success_add_order');
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
		
		return response()->json([
			'status' 	=> $status,
			'message'	=> $msg,
			'errors'	=> $errors,
			'payload'	=> $payload
		], 200, [], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
	}
	
}
