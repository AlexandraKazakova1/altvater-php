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

class UserController extends Controller {
	
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
	
	function login(Request $request){
		$post = $request->all();
		
		$status = false;
		$errors = array();
		$msg	= trans('ajax.failed_login');
		$payload= [];
		
		$validator = Validator::make(
			$post,
			array(
				'email'					=> 'required|email|string|max:100|min:5',
				'password'				=> 'required|min:6|max:12',
			),
			array(
				'email.required'		=> trans('ajax_validation.email_required'),
				'email.email'			=> trans('ajax_validation.email_invalid'),
				
				'password.required'		=> trans('ajax_validation.phone_required'),
				'password.min'			=> trans('ajax_validation.min_length'),
				'password.max'			=> trans('ajax_validation.max_length'),
			)
		);
		
		if($validator->passes()){
			$user = User::query()
						->where('email', '=', $post['email'])
						->first();
			
			if($user){
				if($user->verify_phone > 0){
					if(!isset($post['remember'])){
						$post['remember'] = '';
					}
					
					if(Auth::attempt(['email' => $post['email'], 'password' => $post['password']], $post['remember'] == 'on')){
						$status = true;
						$msg	= trans('ajax.success_login');
					}
				}else{
					$msg	= trans('ajax.user_not_active');
					
					$payload = [
						'sms'	=> true,
						'phone'	=> $user->phone,
						'token'	=> $user->phone_token
					];
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
		
		return response()->json([
			'status' 	=> $status,
			'message'	=> $msg,
			'errors'	=> $errors,
			'payload'	=> $payload
		], 200, [], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
	}
	
	function register(Request $request){
		$post = $request->all();
		
		$status = false;
		$errors = array();
		$msg	= trans('ajax.failed_register');
		$payload= [];
		
		$validator = Validator::make(
			$post,
			array(
				'name'					=> 'required|string|min:2|max:50',
				'telephone'				=> 'required|string|max:17|min:17',
				
				'email'					=> 'required|email|string|max:100|min:5',
				
				'password'				=> 'required|min:6|max:12',
				'confirm'				=> 'required|min:6|max:12',
				
				'addresses'				=> 'max:250',
			),
			array(
				'name.required'			=> trans('ajax_validation.enter_your_name'),
				'name.min'				=> trans('ajax_validation.min_length'),
				'name.max'				=> trans('ajax_validation.max_length'),
				
				'telephone.required'	=> trans('ajax_validation.phone_required'),
				'telephone.min'			=> trans('ajax_validation.min_length'),
				'telephone.max'			=> trans('ajax_validation.max_length'),
				
				'email.required'		=> trans('ajax_validation.email_required'),
				'email.email'			=> trans('ajax_validation.email_invalid'),
				
				'password.required'		=> trans('ajax_validation.phone_required'),
				'password.min'			=> trans('ajax_validation.min_length'),
				'password.max'			=> trans('ajax_validation.max_length'),
				
				'confirm.required'		=> trans('ajax_validation.phone_required'),
				'confirm.min'			=> trans('ajax_validation.min_length'),
				'confirm.max'			=> trans('ajax_validation.max_length'),
				
				'addresses.max'			=> trans('ajax_validation.max_length')
			)
		);
		
		if($validator->passes()){
			$error	= false;
			
			//
			
			if(!isset($post['del-type'])){
				$post['del-type'] = null;
			}
			
			if(!isset($post['pay-method'])){
				$post['pay-method'] = null;
			}
			
			if(!isset($post['np-city-select'])){
				$post['np-city-select'] = null;
			}
			
			if(!isset($post['np-dep-select'])){
				$post['np-dep-select'] = null;
			}
			
			$post['addresses'] = trim($post['addresses']);
			
			//
			
			$post['telephone'] = preg_replace("/[^0-9]/", '', $post['telephone']);
			
			if(strlen($post['telephone']) != 12){
				$error = true;
				
				$msg = trans('ajax_validation.phone_invalid');
			}
			
			if(!$error && $post['del-type']){
				if(!in_array($post['del-type'], ['self-pickup', 'free', 'novaposhta', 'ukrposhta', 'other'])){
					$error = true;
					
					$msg = trans('ajax_validation.choose_delivery_method');
				}
			}
			
			$city = null;
			
			if(!$error && $post['del-type']){
				if($post['del-type'] == 'novaposhta'){
					if(strlen($post['np-city-select']) != 36){
						$error = true;
						
						$msg = trans('ajax_validation.city_delivery_required');
					}else{
						$city		= NPHelper::getCity($post['np-city-select']);
						
						if(!$city){
							$error = true;
							
							$msg = trans('ajax_validation.city_delivery_required');
						}else{
							$city = $city['Ref'];
						}
					}
				}
			}
			
			$department	= null;
			
			if(!$error && $post['del-type']){
				$department_name	= "";
				
				if($post['del-type'] == 'novaposhta'){
					if(strlen($post['np-dep-select']) != 36){
						$error = true;
						
						$msg = trans('ajax_validation.department_required');
					}else{
						$department			= NPHelper::getDepartment($post['np-dep-select']);
						
						if(!$department){
							$error = true;
							
							$msg = trans('ajax_validation.department_required');
						}else{
							$department = $department['Ref'];
						}
					}
				}
			}
			
			if(!$error && $post['del-type']){
				if($post['del-type'] != 'novaposhta' && $post['del-type'] != 'self-pickup'){
					if(!$post['addresses']){
						$error = true;
						
						$msg = trans('ajax_validation.addresses_required');
					}
				}
			}
			
			if(!$error){
				$user = User::query()
							->where('email', '=', $post['email'])
							->first();
				
				if($user){
					if($user->status == 'on'){
						$error = true;
						
						$msg	= trans('ajax_validation.email_unique');
					}
				}else{
					$user = User::query()
								->where('phone', '=', $post['telephone'])
								->first();
					
					if($user){
						if($user->status == 'on'){
							$error = true;
							
							$msg	= trans('ajax_validation.phone_unique');
						}
					}
				}
			}
			
			if(!$error){
				$code = md5(time(). 'r' . $post['email']);
				
				if(!$user){
					$user = User::create([
						'name'				=> $post['name'],
						'email'				=> $post['email'],
						'phone'				=> $post['telephone'],
						'password'			=> Hash::make($post['password']),
						'confirm_code'		=> $code,
						'delivery_type'		=> $post['del-type'],
						'city'				=> $post['np-city-select'],
						'department'		=> $post['np-dep-select'],
						'addresses'			=> $post['addresses'],
					]);
				}else{
					$user->update(['confirm_code'	=>  $code]);
				}
				
				$this->send_email(
					'email-reg',
					$post['email'],
					array(
						'email'	=> $post['email'],
						'url'	=> url('/confirm/'.$code),
						'code'	=> $code,
						'name'  => $post['name']
					)
				);
				
				$status = true;
				$msg	= trans('ajax.success_register');
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
	
	function activation(Request $request){
		$post = $request->all();
		
		$status = false;
		$errors = array();
		$msg	= trans('ajax.failed_activation');
		$payload= [];
		
		$validator = Validator::make(
			$post,
			array(
				'token'					=> 'required|string|max:32|min:32',
				'code'					=> 'required',
			),
			array(
				'token.required'		=> trans('ajax_validation.phone_required'),
				'token.min'				=> trans('ajax_validation.min_length'),
				'token.max'				=> trans('ajax_validation.max_length'),
				
				'code.required'			=> trans('ajax_validation.required'),
			)
		);
		
		if($validator->passes()){
			if(is_array($post['code'])){
				$post['code'] = implode('', $post['code']);
			}
			
			$user = User::query()
						->where('phone_token', '=', $post['token'])
						//->where('phone_code', '=', $post['code'])
						->first();
			
			if($user){
				if($user->phone_code == $post['code']){
					if(!isset($post['remember'])){
						$post['remember'] = '';
					}
					
					if(Auth::loginUsingId($user->id, $post['remember'] == 'on')){
						$status = true;
						$msg	= trans('ajax.success_activation');
					}
				}else{
					$msg	= trans('ajax.code_invalid');
				}
			}else{
				$msg	= trans('ajax.user_not_found');
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
	
	function forgotten(Request $request){
		$post = $request->all();
		
		$status = false;
		$errors = array();
		$msg	= trans('ajax.failed_forgotten');
		$payload= [];
		
		$validator = Validator::make(
			$post,
			array(
				'email'					=> 'required|email|string|max:100',
			),
			array(
				'email.required'		=> trans('ajax_validation.email_required'),
				'email.email'			=> trans('ajax_validation.email_invalid'),
			)
		);
		
		if($validator->passes()){
			$error	= false;
			
			$user = User::query()
							->where('email', '=', $post['email'])
							->first();
			
			if(!$user){
				$error = true;
				
				$msg	= trans('ajax.user_not_found');
			}
			
			if(!$error){
				$code = md5(time(). 'r' . $post['email']);
				
				$user->update(['confirm_code'	=>  $code]);
				
				$this->send_email('reset-password',
					$post['email'],
					array(
						'email'	=> $post['email'],
						'url'	=> url('/reset/'.$code),
						'code'	=> $code,
						'name'  => $user->name
					)
				);
				
				$status = true;
				$msg	= trans('ajax.success_register');
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
	
	function settings(Request $request){
		$this->session();
				
		$status = false;
		$errors = array();
		$msg	= trans('ajax.failed_profile');
		$payload= [];
		
		if(!$this->_auth){
			return response()->json([
				'status' 	=> $status,
				'message'	=> $msg,
				'errors'	=> $errors,
				'payload'	=> $payload
			], 200, [], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
		}
		
		$post = $request->all();
		
		$validator = Validator::make(
			$post,
			array(
				'name'					=> 'required|string|min:2|max:50',
				'surname'				=> 'required|string|min:2|max:50',
				'middlename'			=> 'required|string|min:2|max:50',
				
				//'email'					=> 'required|email',
				
				//'phone'					=> 'required|string|min:12|max:13',
				'extra_phone'			=> 'min:12|max:13',
				
				'address'				=> 'string|max:150',
				
				'index'					=> 'string|max:6',
			),
			array(
				'name.required'			=> trans('ajax_validation.required'),
				'name.min'				=> trans('ajax_validation.min_length'),
				'name.max'				=> trans('ajax_validation.max_length'),
				
				'surname.required'		=> trans('ajax_validation.required'),
				'surname.min'			=> trans('ajax_validation.min_length'),
				'surname.max'			=> trans('ajax_validation.max_length'),
				
				'middlename.required'	=> trans('ajax_validation.required'),
				'middlename.min'		=> trans('ajax_validation.min_length'),
				'middlename.max'		=> trans('ajax_validation.max_length'),
				
				'email.required'		=> trans('ajax_validation.email_required'),
				'email.email'			=> trans('ajax_validation.email_invalid'),
				
				'phone.required'		=> trans('ajax_validation.phone_required'),
				'phone.min'				=> trans('ajax_validation.min_length'),
				'phone.max'				=> trans('ajax_validation.max_length'),
				
				'extra_phone.required'	=> trans('ajax_validation.phone_required'),
				'extra_phone.min'		=> trans('ajax_validation.min_length'),
				'extra_phone.max'		=> trans('ajax_validation.max_length'),
				
				'address.max'			=> trans('ajax_validation.max_length'),
				
				'index.max'				=> trans('ajax_validation.max_length')
			)
		);
		
		if($validator->passes()){
			$error	= false;
			
			if(false){
				$post['phone'] = preg_replace("/[^0-9]/", '', $post['phone']);
				
				if(strlen($post['phone']) != 12){
					$error = true;
					
					$msg = trans('ajax_validation.phone_invalid');
				}
			}
			
			$post['extra_phone'] = preg_replace("/[^0-9]/", '', $post['extra_phone']);
			
			if($post['extra_phone']){
				if(strlen($post['extra_phone']) != 12){
					$error = true;
					
					$msg = trans('ajax_validation.phone_invalid');
				}
			}
			
			$post['index'] = preg_replace("/[^0-9]/", '', $post['index']);
			
			if(!$error){
				User::query()
							->where('id', $this->_id)
							->update([
								'name'				=> $post['name'],
								'surname'			=> $post['surname'],
								'middlename'		=> $post['middlename'],
								
								'extra_phone'		=> $post['extra_phone'],
								
								'addresses'			=> $post['address'],
								'index'				=> $post['index'],
							]);
				
				$status = true;
				$msg	= trans('ajax.success_profile');
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
