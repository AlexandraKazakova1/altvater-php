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
use App\Models\Contracts;
use App\Models\Connects;

class ContractsController extends Controller {
	
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
		
		if(!$this->_auth){
			return response()->json([
				'status' 	=> $status,
				'message'	=> $msg,
				'errors'	=> $errors,
				'payload'	=> $payload
			], 200, [], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
		}
		
		if($this->_user->type == 'individual'){
			return $this->add_individual($request);
		}
		
		return $this->add_entity($request);
	}
	
	function add_individual(Request $request){
		$post = $request->all();
		
		$status = false;
		$errors = array();
		$msg	= trans('ajax.failed_add_contract');
		$payload= [];
		
		$validator = Validator::make(
			$post,
			array(
				'name'					=> 'required|string|min:2|max:100',
				'addresses'				=> 'required|string|min:2|max:150',
				'email'					=> 'required|email',
				'phone'					=> 'required|string|min:9|max:13',
				'index'					=> 'required'
			),
			array(
				'name.required'			=> trans('ajax_validation.required'),
				'name.min'				=> trans('ajax_validation.min_length'),
				'name.max'				=> trans('ajax_validation.max_length'),
				
				'contact.required'		=> trans('ajax_validation.required'),
				'contact.min'			=> trans('ajax_validation.min_length'),
				'contact.max'			=> trans('ajax_validation.max_length'),
				
				'addresses.required'		=> trans('ajax_validation.required'),
				'addresses.min'			=> trans('ajax_validation.min_length'),
				'addresses.max'			=> trans('ajax_validation.max_length'),
				
				'email.required'		=> trans('ajax_validation.required'),
				'email.min'				=> trans('ajax_validation.min_length'),
				'email.max'				=> trans('ajax_validation.max_length'),
				'email.email'			=> trans('ajax_validation.email'),
			)
		);
		
		if($validator->passes()){
			$error	= false;
			
			$post['phone']			= preg_replace("/[^0-9]/", '', $post['phone']);
		//	$post['index']			= preg_replace("/[^0-9]/", '', $post['index']);
			
			//
			
			if(!$error){
				$record = Contracts::create([
					'client_id'			=> $this->_id,
					'name'				=> $post['name'],
					'email'				=> $post['email'],
					'phone'				=> $post['phone'],
					'address'			=> $post['addresses'],
					'index'				=> $post['index']
				]);
				
				$status = true;
				$msg	= trans('ajax.success_add_contract');
				
				$this->sendEmail(
					'new-contract',
					null,
					array(
						'name'	=> $this->_user->name,
						'id'	=> $this->_id,
						'url'	=> url('/admin/contracts/'.$record->id.'/edit')
						
					)
				);
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
	
	function add_entity(Request $request){
		$post = $request->all();
		
		$status = false;
		$errors = array();
		$msg	= trans('ajax.failed_add_contract');
		$payload= [];
		
		$validator = Validator::make(
			$post,
			array(
				'company_name'			=> 'required|string|min:2|max:100',
				'name'					=> 'required|string|min:2|max:100',
				'addresses'				=> 'required|string|min:2|max:150',
				'email'					=> 'required|email',
				'phone'					=> 'required|string|min:9|max:13',
				'extra_phone'			=> 'min:9|max:13',
				'index'					=> 'required|min:5|max:6',
				'ipn'					=> 'required|min:10|max:12',
				'edrpou'				=> 'required',
			),
			array(
				'company_name.required'	=> trans('ajax_validation.required'),
				'company_name.min'		=> trans('ajax_validation.min_length'),
				'company_name.max'		=> trans('ajax_validation.max_length'),
				
				'name.required'			=> trans('ajax_validation.required'),
				'name.min'				=> trans('ajax_validation.min_length'),
				'name.max'				=> trans('ajax_validation.max_length'),
				
				'address.required'		=> trans('ajax_validation.required'),
				'address.min'			=> trans('ajax_validation.min_length'),
				'address.max'			=> trans('ajax_validation.max_length'),
				
				'email.required'		=> trans('ajax_validation.required'),
				'email.min'				=> trans('ajax_validation.min_length'),
				'email.max'				=> trans('ajax_validation.max_length'),
				'email.email'			=> trans('ajax_validation.email'),
				
				'phone.required'		=> trans('ajax_validation.required'),
				'phone.min'				=> trans('ajax_validation.min_length'),
				'phone.max'				=> trans('ajax_validation.max_length'),
				
				'extra_phone.required'	=> trans('ajax_validation.required'),
				'extra_phone.min'		=> trans('ajax_validation.min_length'),
				'extra_phone.max'		=> trans('ajax_validation.max_length'),
				
				'index.required'		=> trans('ajax_validation.required'),
				'index.min'				=> trans('ajax_validation.min_length'),
				'index.max'				=> trans('ajax_validation.max_length'),
				
				'ipn.required'			=> trans('ajax_validation.required'),
				'ipn.min'				=> trans('ajax_validation.min_length'),
				'ipn.max'				=> trans('ajax_validation.max_length'),
				
				'edrpou.required'		=> trans('ajax_validation.required'),
				'edrpou.min'			=> trans('ajax_validation.min_length'),
				'edrpou.max'			=> trans('ajax_validation.max_length'),
			)
		);
		
		if($validator->passes()){
			$error	= false;
			
			$post['phone']			= preg_replace("/[^0-9]/", '', $post['phone']);
			$post['extra_phone']	= preg_replace("/[^0-9]/", '', $post['extra_phone']);
			//$post['index']			= preg_replace("/[^0-9]/", '', $post['index']);
			$post['ipn']			= preg_replace("/[^0-9]/", '', $post['ipn']);
			$post['edrpou']			= preg_replace("/[^0-9]/", '', $post['edrpou']);
			
			//
			
			if(!$error){
				$record = Contracts::create([
					'client_id'			=> $this->_id,
					'name'				=> $post['company_name'],
					'contact'			=> $post['name'],
					'email'				=> $post['email'],
					'phone'				=> $post['phone'],
					'extra_phone'		=> $post['extra_phone'],
					'index'				=> $post['index'],
					'ipn'				=> $post['ipn'],
					'edrpou'			=> $post['edrpou'],
				]);
				
				$status = true;
				$msg	= trans('ajax.success_add_contract');
				
				$this->sendEmail(
					'new-contract',
					null,
					array(
						'name'	=> $this->_user->name,
						'id'	=> $this->_id,
						'url'	=> url('/admin/contracts/'.$record->id.'/edit')
						
					)
				);
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
	
	function contracts_list(Request $request){
		$this->session();
		
		$status = false;
		$errors = array();
		$msg	= '';
		$payload= [
			'html'	=> '',
			'count'	=> 0
		];
		
		$type = $request->route('type');
		
		if($type != "active" && $type != "archive"){
			return response()->json([
				'status' 	=> $status,
				'message'	=> $msg,
				'errors'	=> $errors,
				'payload'	=> $payload
			], 200, [], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
		}
		
		$sort = $request->get('sort');
		
		if($sort != "date" && $sort != "number"){
			return response()->json([
				'status' 	=> $status,
				'message'	=> $msg,
				'errors'	=> $errors,
				'payload'	=> $payload
			], 200, [], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
		}
		
		$column = 'created_at';
		
		if($sort == "number"){
			$column = 'number';
		}
		
		$status = true;
		
		$payload['count'] = Contracts::query()->where('client_id', $this->_id)->whereRaw(DB::raw('(`archive` IS NULL OR `archive` = 0)'))->count();
		
		$offset = (int)$request->get('offset');
		$limit	= 4;
		
		if($offset < 0){
			$offset = 0;
		}else{
			if($offset > $payload['count']){
				$offset = $payload['count'];
			}
		}
		
		if($type == "active"){
			$data = Contracts::query()
							->where('client_id', $this->_id)
							->whereRaw(DB::raw('(`archive` IS NULL OR `archive` = 0)'))
							->skip($offset)
							->take($limit)
							->orderBy($column, 'desc')
							->get();
			
			$payload['html'] = view(
									'account.components.active_contracts',
									[
										'contracts'	=> $data
									]
								)
								->render();
		}else{
			$data = Contracts::query()
							->where('client_id', $this->_id)
							->where('archive', 1)
							->skip($offset)
							->take($limit)
							->orderBy($column, 'desc')
							->get();
			
			$payload['html'] = view(
									'account.components.archive_contracts',
									[
										'contracts'	=> $data
									]
								)
								->render();
		}
		
		return response()->json([
			'status' 	=> $status,
			'message'	=> $msg,
			'errors'	=> $errors,
			'payload'	=> $payload
		], 200, [], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
	}
	
	function connect_contract(Request $request){
		$this->session();
		
		if(!$this->_auth){
			return response()->json([
				'status' 	=> $status,
				'message'	=> $msg,
				'errors'	=> $errors,
				'payload'	=> $payload
			], 200, [], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
		}
		
		$post = $request->all();
		
		$status = false;
		$errors = array();
		$msg	= trans('ajax.failed_connect_contract');
		$payload= [];
		
		if($this->_user->type == 'individual'){
			$rules = array(
				'name'					=> 'required|string|min:2|max:100',
				'number'				=> 'required|max:15'
			);
		}else{
			$rules = array(
				'edrpou'				=> 'required|string|min:8|max:50',
				'number'				=> 'required|max:15'
			);
		}
		
		$validator = Validator::make(
			$post,
			$rules,
			array(
				'name.required'			=> trans('ajax_validation.required'),
				'name.min'				=> trans('ajax_validation.min_length'),
				'name.max'				=> trans('ajax_validation.max_length'),
				
				'edrpou.required'		=> trans('ajax_validation.required'),
				'edrpou.min'			=> trans('ajax_validation.min_length'),
				'edrpou.max'			=> trans('ajax_validation.max_length'),
				
				'number.required'		=> trans('ajax_validation.required'),
				'number.min'			=> trans('ajax_validation.min_length'),
				'number.max'			=> trans('ajax_validation.max_length'),
			)
		);
		
		if($validator->passes()){
			$error	= false;
			
			if(!$error){
				if($this->_user->type == 'individual'){
					$record = Contracts::query()->where('number', $post['number'])->first();
					
					if(!$record){
						return response()->json([
							'status' 	=> false,
							'message'	=> trans('ajax.contract_not_found'),
							'errors'	=> [],
							'payload'	=> []
						], 200, [], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
					}
					
					$connect = Connects::create([
						'client_id'		=> $this->_id,
						'contract_id'	=> $record->id,
						'number'		=> $post['number'],
						'name'			=> $post['name'],
						'edrpou'		=> null,
					]);
					
					//$record->update(['client_id' => $this->_id]);
				}else{
					$record = Contracts::query()->where('number', $post['number'])->first();
					
					if($record){
						$counterparty = User::query()->where('uedrpou', $post['edrpou'])->first();
						
						if(!$counterparty){
							$connect = Connects::create([
								'client_id'		=> $this->_id,
								'contract_id'	=> $record->id,
								'number'		=> $post['number'],
								'name'			=> null,
								'edrpou'		=> $post['edrpou'],
							]);
						}else{
							$check = Contracts::query()->where('id', $record->id)->where('client_id', $counterparty->id)->first();
							
							if(!$check){
								return response()->json([
									'status' 	=> false,
									'message'	=> trans('ajax.data_do_not_match'),
									'errors'	=> [],
									'payload'	=> []
								], 200, [], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
							}else{
								$connect = Connects::create([
									'client_id'		=> $this->_id,
									'contract_id'	=> $record->id,
									'number'		=> $post['number'],
									'name'			=> null,
									'edrpou'		=> $post['edrpou'],
								]);
							}
						}
					}else{
						$connect = Connects::create([
							'client_id'	=> $this->_id,
							'number'	=> $post['number'],
							'name'		=> null,
							'edrpou'	=> $post['edrpou'],
						]);
					}
				}
				
				$status = true;
				$msg	= trans('ajax.success_connect_contract');
				
				$this->sendEmail(
					'connect-contract',
					null,
					array(
						'name'		=> $this->_user->name,
						'number'	=> $post['number'],
						'id'		=> $this->_id,
						'url'		=> url('/admin/connects/'.$connect->id.'/edit')
						
					)
				);
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
