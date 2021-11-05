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
use App\Helpers\SMSClub;
use App\Helpers\smsc;

use App\Models\User;

class UserController extends Controller {
	
	public $_auth	= false;
	public $_user	= [];
	public $_id		= 0;
	
	public $_send_sms = false;
	
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
		
		$payload = [
			'sms'			=> false,
			'phone'			=> '',
			'token'			=> '',
			"phone_format"	=> ""
		];
		
		$validator = Validator::make(
			$post,
			array(
				'email'					=> 'required|email|string|max:100|min:5',
				'password'				=> 'required|min:6|max:12',
			),
			array(
				'email.required'		=> trans('ajax_validation.email_required'),
				'email.email'			=> trans('ajax_validation.email_invalid'),
				
				'password.required'		=> trans('ajax_validation.password_required'),
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
						'sms'			=> true,
						'phone'			=> $user->phone,
						"phone_format"	=> StringHelper::phone($user->phone, '[2] [(3)] 2-2-3'),
						"email"			=> $user->email,
						'token'			=> $user->phone_token
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
	
	function registration(Request $request){
		$type = $request->get('user-type');
		
		if($type == 'individual'){
			return $this->registrationIndividual($request);
		}
		
		if($type == 'legal-entity'){
			return $this->registrationLegal($request);
		}
		
		return response()->json([
			'status' 	=> false,
			'message'	=> trans('ajax.failed_register'),
			'errors'	=> array(),
			'payload'	=> array()
		], 200, [], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
	}
	
	function registrationIndividual(Request $request){
		$post = $request->all();
		
		$status = false;
		$errors = array();
		$msg	= trans('ajax.failed_register');
		$payload= [];
		
		$validator = Validator::make(
			$post,
			array(
				'user-type'				=> 'required',
				
				'name'					=> 'required|string|min:2|max:50',
				
				'phone'					=> 'required|string|min:11|max:17',
				
				'email'					=> 'required|email|string|max:100|min:5',
				
				'password'				=> 'required|min:6|max:12',
				'confirm_password'		=> 'required|min:6|max:12',
			),
			array(
				'name.required'					=> trans('ajax_validation.enter_your_name'),
				'name.min'						=> trans('ajax_validation.min_length'),
				'name.max'						=> trans('ajax_validation.max_length'),
				
				'phone.required'				=> trans('ajax_validation.phone_required'),
				'phone.min'						=> trans('ajax_validation.min_length'),
				'phone.max'						=> trans('ajax_validation.max_length'),
				
				'email.required'				=> trans('ajax_validation.email_required'),
				'email.email'					=> trans('ajax_validation.email_invalid'),
				
				'password.required'				=> trans('ajax_validation.password_required'),
				'password.min'					=> trans('ajax_validation.min_length'),
				'password.max'					=> trans('ajax_validation.max_length'),
				
				'confirm_password.required'		=> trans('ajax_validation.password_required'),
				'confirm_password.min'			=> trans('ajax_validation.min_length'),
				'confirm_password.max'			=> trans('ajax_validation.max_length'),
			)
		);
		
		if($validator->passes()){
			$error	= false;
			
			//
			
			if($post['password'] != $post['confirm_password']){
				return response()->json([
					'status' 	=> false,
					'message'	=> trans('ajax.passwords_not_match'),
					'errors'	=> [],
					'payload'	=> []
				], 200, [], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
			}
			
			$post['phone'] = preg_replace("/[^0-9]/", '', $post['phone']);
			
			if(strlen($post['phone']) != 12){
				$error = true;
				
				$msg = trans('ajax_validation.phone_invalid');
			}
			
			if(!$error){
				$check = User::query()
							->where('phone', '=', $post['phone'])
							->first();
				
				if($check){
					$error = true;
					
					$msg	= trans('ajax_validation.phone_unique');
				}
			}
			
			if(!$error){
				$user = User::query()
							->where('email', '=', $post['email'])
							->first();
				
				if($user){
					$error = true;
					
					$msg	= trans('ajax_validation.email_unique');
				}
			}
			
			if(!$error){
				$email_token	= md5(time(). 'r' . $post['email']);
				
				if($this->_send_sms){
					$phone_code		= mt_rand(11111, 99999);
				}else{
					$phone_code		= 11111;
				}
				
				$phone_token	= md5(time(). 'p' . $phone_code);
				
				$user = User::create([
					'type'				=> $post['user-type'],
					'name'				=> $post['name'],
					'email'				=> $post['email'],
					'phone'				=> $post['phone'],
					'password'			=> Hash::make($post['password']),
					'email_token'		=> $email_token,
					'phone_code'		=> $phone_code,
					'phone_token'		=> $phone_token,
				]);
				
				$this->sendEmail(
					'email-reg',
					$post['email'],
					array(
						'email'	=> $post['email'],
						'url'	=> url('/confirm/'.$email_token),
						'code'	=> $email_token,
						'name'  => $post['name']
					)
				);
				
				if($this->_send_sms){
					$sms = new smsc();
					
					$sms->config_user(env('SMSCRU_LOGIN'), env('SMSCRU_PASSWORD'));
					
					$result = $sms->send(
						$post['phone'],
						[
							'code' => $phone_code
						],
						'reg'
					);
				}else{
					$result = true;
				}
				
				if(!$result){
					return response()->json([
						'status' 	=> false,
						'message'	=> trans('ajax.failed_send_sms'),
						'errors'	=> [],
						'payload'	=> []
					], 200, [], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
				}
				
				$status = true;
				$msg	= trans('ajax.success_register');
				
				$payload = [
					"phone"			=> $post['phone'],
					"phone_format"	=> StringHelper::phone($post['phone'], '[2] [(3)] 2-2-3'),
					"email"			=> $post['email'],
					"token" 		=> $phone_token,
				];
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
	
	function registrationLegal(Request $request){
		$post = $request->all();
		
		$status = false;
		$errors = array();
		$msg	= trans('ajax.failed_register');
		$payload= [];
		
		$validator = Validator::make(
			$post,
			array(
				'user-type'				=> 'required',
				
				'company_name'			=> 'required|string|min:2|max:100',
				
				'name'					=> 'required|string|min:2|max:50',
				
				'addresses'				=> 'required|string|min:2|max:150',
				
				'phone'					=> 'required|string|min:11|max:17',
				'extra_prone'			=> 'min:11|max:17',
				
				'email'					=> 'required|email|string|max:100|min:5',
				
				'password'				=> 'required|min:6|max:12',
				'confirm_password'		=> 'required|min:6|max:12',
				
				'ipn'					=> 'required|min:10|max:10',
				'uedrpou'				=> 'required|min:8|max:50',
				'index'					=> 'required|min:6|max:10',
			),
			array(
				'company_name.required'			=> trans('ajax_validation.enter_name_company'),
				'company_name.min'				=> trans('ajax_validation.min_length'),
				'company_name.max'				=> trans('ajax_validation.max_length'),
				
				'name.required'					=> trans('ajax_validation.enter_name_contact'),
				'name.min'						=> trans('ajax_validation.min_length'),
				'name.max'						=> trans('ajax_validation.max_length'),
				
				'addresses.required'			=> trans('ajax_validation.addresses_required'),
				'addresses.min'					=> trans('ajax_validation.min_length'),
				'addresses.max'					=> trans('ajax_validation.max_length'),
				
				'phone.required'				=> trans('ajax_validation.phone_required'),
				'phone.min'						=> trans('ajax_validation.min_length'),
				'phone.max'						=> trans('ajax_validation.max_length'),
				
				'extra_prone.required'			=> trans('ajax_validation.phone_required'),
				'extra_prone.min'				=> trans('ajax_validation.min_length'),
				'extra_prone.max'				=> trans('ajax_validation.max_length'),
				
				'email.required'				=> trans('ajax_validation.email_required'),
				'email.email'					=> trans('ajax_validation.email_invalid'),
				
				'password.required'				=> trans('ajax_validation.password_required'),
				'password.min'					=> trans('ajax_validation.min_length'),
				'password.max'					=> trans('ajax_validation.max_length'),
				
				'confirm_password.required'		=> trans('ajax_validation.password_required'),
				'confirm_password.min'			=> trans('ajax_validation.min_length'),
				'confirm_password.max'			=> trans('ajax_validation.max_length'),
				
				'ipn.required'					=> trans('ajax_validation.required'),
				'ipn.min'						=> trans('ajax_validation.min_length'),
				'ipn.max'						=> trans('ajax_validation.max_length'),
				
				'uedrpou.required'				=> trans('ajax_validation.required'),
				'uedrpou.min'					=> trans('ajax_validation.min_length'),
				'uedrpou.max'					=> trans('ajax_validation.max_length'),
				
				'index.required'				=> trans('ajax_validation.required'),
				'index.min'						=> trans('ajax_validation.min_length'),
				'index.max'						=> trans('ajax_validation.max_length'),
			)
		);
		
		if($validator->passes()){
			$error	= false;
			
			//
			
			if($post['password'] != $post['confirm_password']){
				return response()->json([
					'status' 	=> false,
					'message'	=> trans('ajax.passwords_not_match'),
					'errors'	=> [],
					'payload'	=> []
				], 200, [], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
			}
			
			$post['phone'] = preg_replace("/[^0-9]/", '', $post['phone']);
			
			if(strlen($post['phone']) != 12){
				$error = true;
				
				$msg = trans('ajax_validation.phone_invalid');
			}
			
			if(!$error){
				$check = User::query()
							->where('phone', '=', $post['phone'])
							->first();
				
				if($check){
					$error = true;
					
					$msg	= trans('ajax_validation.phone_unique');
				}
			}
			
			if(!$error){
				$user = User::query()
							->where('email', '=', $post['email'])
							->first();
				
				if($user){
					$error = true;
					
					$msg	= trans('ajax_validation.email_unique');
				}
			}
			
			if(!$error){
				$post['ipn']		= preg_replace("/[^0-9]/", '', $post['ipn']);
				//$post['uedrpou']	= preg_replace("/[^0-9]/", '', $post['phone']);
				$post['index']		= preg_replace("/[^0-9]/", '', $post['index']);
				
				$post['extra_prone'] = preg_replace("/[^0-9]/", '', $post['extra_prone']);
				
				$email_token	= md5(time(). 'r' . $post['email']);
				
				if($this->_send_sms){
					$phone_code		= mt_rand(11111, 99999);
				}else{
					$phone_code		= 11111;
				}
				
				$phone_token	= md5(time(). '-' . $phone_code);
				
				$user = User::create([
					'type'				=> $post['user-type'],
					'company_name'		=> $post['company_name'],
					'name'				=> $post['name'],
					'addresses'			=> $post['addresses'],
					'ipn'				=> $post['ipn'],
					'uedrpou'			=> $post['uedrpou'],
					'index'				=> $post['index'],
					'email'				=> $post['email'],
					'phone'				=> $post['phone'],
					'extra_prone'		=> $post['extra_prone'],
					'password'			=> Hash::make($post['password']),
					'email_token'		=> $email_token,
					'phone_code'		=> $phone_code,
					'phone_token'		=> $phone_token,
				]);
				
				$this->sendEmail(
					'email-reg',
					$post['email'],
					array(
						'email'	=> $post['email'],
						'url'	=> url('/confirm/'.$email_token),
						'code'	=> $email_token,
						'name'  => $post['name']
					)
				);
				
				if($this->_send_sms){
					$sms = new smsc();
					
					$sms->config_user(env('SMSCRU_LOGIN'), env('SMSCRU_PASSWORD'));
					
					$result = $sms->send(
						$post['phone'],
						[
							'code' => $phone_code
						],
						'reg'
					);
				}else{
					$result = true;
				}
				
				if(!$result){
					return response()->json([
						'status' 	=> false,
						'message'	=> trans('ajax.failed_send_sms'),
						'errors'	=> [],
						'payload'	=> []
					], 200, [], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
				}
				
				$status = true;
				$msg	= trans('ajax.success_register');
				
				$payload = [
					"phone"			=> $post['phone'],
					"phone_format"	=> StringHelper::phone($post['phone'], '[2] [(3)] 2-2-3'),
					"email"			=> $post['email'],
					"token" 		=> $phone_token,
				];
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
	
	function verification(Request $request){
		$post = $request->all();
		
		$status = false;
		$errors = array();
		$msg	= trans('ajax.failed_activation');
		$payload= [];
		
		$validator = Validator::make(
			$post,
			array(
				'token'					=> 'required|string|max:32|min:32',
				'verifCode'				=> 'required',
			),
			array(
				'token.required'		=> trans('ajax_validation.phone_required'),
				'token.min'				=> trans('ajax_validation.min_length'),
				'token.max'				=> trans('ajax_validation.max_length'),
				
				'verifCode.required'	=> trans('ajax_validation.required'),
			)
		);
		
		if($validator->passes()){
			if(is_array($post['verifCode'])){
				$post['verifCode'] = implode('', $post['verifCode']);
			}
			
			$user = User::query()
						->where('phone_token', '=', $post['token'])
						//->where('phone_code', '=', $post['code'])
						->first();
			print_r($user);
			exit;
			if($user){
				if($user->phone_code == $post['verifCode']){
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
	
	function change_password(Request $request){
		$this->session();
				
		$status = false;
		$errors = array();
		$msg	= trans('ajax.failed_change_password');
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
				'password'					=> 'required|min:6|max:12',
				'new_password'				=> 'required|min:6|max:12',
				'confirm_password'			=> 'required|min:6|max:12',
			),
			array(
				'password.required'			=> trans('ajax_validation.password_required'),
				'password.min'				=> trans('ajax_validation.min_length'),
				'password.max'				=> trans('ajax_validation.max_length'),
				
				'new_password.required'		=> trans('ajax_validation.password_required'),
				'new_password.min'			=> trans('ajax_validation.min_length'),
				'new_password.max'			=> trans('ajax_validation.max_length'),
				
				'confirm_password.required'	=> trans('ajax_validation.password_required'),
				'confirm_password.min'		=> trans('ajax_validation.min_length'),
				'confirm_password.max'		=> trans('ajax_validation.max_length'),
			)
		);
		
		if($validator->passes()){
			$user = User::query()
						->where('id', '=', $this->_id)
						->first();
			
			if($user){
				if(!Hash::check($post['password'], $user->password)){
					return response()->json([
						'status' 	=> false,
						'message'	=> trans('ajax.password_incorrect'),
						'errors'	=> [],
						'payload'	=> []
					], 200, [], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
				}
				
				if($post['new_password'] != $post['confirm_password']){
					return response()->json([
						'status' 	=> false,
						'message'	=> trans('ajax.passwords_not_match'),
						'errors'	=> [],
						'payload'	=> []
					], 200, [], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
				}
				
				$user->update([
					'password' => bcrypt($post['new_password'])
				]);
				
				$status = true;
				$msg	= trans('ajax.success_change_password');
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
