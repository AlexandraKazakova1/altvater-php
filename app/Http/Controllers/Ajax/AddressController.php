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
use App\Models\UserAddresses;

class AddressController extends Controller {
	
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
		$msg	= trans('ajax.failed_add_contract');
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
				'name'					=> 'required|string|min:2|max:200',
				'contact'				=> 'required|string|min:2|max:100',
				'address'				=> 'required|string|min:2|max:150',
				'email'					=> 'required|email',
				'phone'					=> 'required|string|min:9|max:13',
				'extra_phone'			=> 'max:13',
				'index'					=> 'required',
				'ipn'					=> 'required',
				'edrpou'				=> 'required',
			),
			array(
				'name.required'			=> trans('ajax_validation.required'),
				'name.min'				=> trans('ajax_validation.min_length'),
				'name.max'				=> trans('ajax_validation.max_length'),
				
				'contact.required'		=> trans('ajax_validation.required'),
				'contact.min'			=> trans('ajax_validation.min_length'),
				'contact.max'			=> trans('ajax_validation.max_length'),
				
				'address.required'		=> trans('ajax_validation.required'),
				'address.min'			=> trans('ajax_validation.min_length'),
				'address.max'			=> trans('ajax_validation.max_length'),
				
				'email.required'		=> trans('ajax_validation.required'),
				'email.min'				=> trans('ajax_validation.min_length'),
				'email.max'				=> trans('ajax_validation.max_length'),
				'email.email'			=> trans('ajax_validation.email'),
			)
		);
		
		if($validator->passes()){
			$error	= false;
			
			$post['phone']			= preg_replace("/[^0-9]/", '', $post['phone']);
			$post['extra_phone']	= preg_replace("/[^0-9]/", '', $post['extra_phone']);
			$post['index']			= preg_replace("/[^0-9]/", '', $post['index']);
			$post['ipn']			= preg_replace("/[^0-9]/", '', $post['ipn']);
			$post['edrpou']			= preg_replace("/[^0-9]/", '', $post['edrpou']);
			
			//
			
			if(!$error){
				$record = UserAddresses::create([
					'client_id'			=> $this->_id,
					'name'				=> $post['name'],
					'contact'			=> $post['contact'],
					'email'				=> $post['email'],
					'phone'				=> $post['phone'],
					'extra_phone'		=> $post['extra_phone'],
					'index'				=> $post['index'],
					'ipn'				=> $post['ipn'],
					'edrpou'			=> $post['edrpou'],
				]);
				
				$status = true;
				$msg	= trans('ajax.success_add_address');
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
	
	function remove(Request $request){
		$this->session();
		
		$status = false;
		$errors = array();
		$msg	= trans('ajax.failed_remove_address');
		$payload= [];
		
		if(!$this->_auth){
			return response()->json([
				'status' 	=> $status,
				'message'	=> $msg,
				'errors'	=> $errors,
				'payload'	=> $payload
			], 200, [], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
		}
		
		$id = (int)$request->get('id');
		
		if($id){
			UserAddresses::query()->where('id', $id)->delete();
		}
		
		return response()->json([
			'status' 	=> $status,
			'message'	=> $msg,
			'errors'	=> $errors,
			'payload'	=> $payload
		], 200, [], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
	}
}
