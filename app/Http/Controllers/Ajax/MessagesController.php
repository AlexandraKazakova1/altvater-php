<?php

namespace App\Http\Controllers\Ajax;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

use App\Helpers\StringHelper;

use App\Models\User;
use App\Models\Themes;
use App\Models\Dialogues;
use App\Models\Messages;
use App\Models\Contracts;

class MessagesController extends Controller {
	
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
		$msg	= trans('ajax.failed_create_request');
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
				'theme'				=> 'required',
				'header'			=> 'required|min:2|max:150',
				'phone'				=> 'max:13',
				'text'				=> 'required|min:5|max:1000',
			),
			array(
				'theme.required'	=> trans('ajax_validation.required'),
				
				'header.required'	=> trans('ajax_validation.required'),
				'header.min'		=> trans('ajax_validation.min_length'),
				'header.max'		=> trans('ajax_validation.max_length'),
				
				'phone.required'	=> trans('ajax_validation.required'),
				'phone.min'			=> trans('ajax_validation.min_length'),
				'phone.max'			=> trans('ajax_validation.max_length'),
				
				'text.required'		=> trans('ajax_validation.required'),
				'text.min'			=> trans('ajax_validation.min_length'),
				'text.max'			=> trans('ajax_validation.max_length'),
			)
		);
		
		if($validator->passes()){
			$error = false;
			
			$theme_id		= (int)$request->get('theme');
			$contract_id	= (int)$request->get('number');
			
			if(!$theme_id){
				$error	= true;
				$msg	= trans('ajax.select_theme');
			}
			
			if(!$error){
				$theme = Themes::query()->where('id', $theme_id)->first();
				
				if(!$theme){
					$error	= true;
					$msg	= trans('ajax.select_theme');
				}
			}
			
			if($contract_id > 0){
				$contract = Contracts::query()->where('id', $contract_id)->first();
				
				if(!$contract){
					$error	= true;
				}
			}else{
				$contract_id = null;
			}
			
			if(!$error){
				$post['phone'] = preg_replace("/[^0-9]/", '', $post['phone']);
				
				if($post['phone']){
					if(strlen($post['phone']) != 12){
						$error = true;
						
						$msg = trans('ajax_validation.phone_invalid');
					}
				}
			}
			
			if(!$error){
				$file = null;
				
				$tmp_file	= $request->get('file');
				
				if($tmp_file){
					if(isset($tmp_file['name']) && isset($tmp_file['mime']) && isset($tmp_file['data'])){
						$tmp_file['mime'] = explode('/', $tmp_file['mime']);
						
						if($tmp_file['mime'][0] == 'image' || $tmp_file['mime'][0] == 'application'){
							$file	= md5(time().'-'.$tmp_file['mime'][1]).'.'.$tmp_file['mime'][1];
							
							Storage::put('chats/'.$file, base64_decode($tmp_file['data']));
						}
					}
				}
			}
			
			if(!$error){
				$record = Dialogues::create([
					"client_id"		=> $this->_id,
					"theme_id"		=> $theme_id,
					"contract_id"	=> $contract_id,
					"phone"			=> $post['phone'],
					"header"		=> $post['header'],
					"file"			=> $file ? 'chats/'.$file : null
				]);
				
				$message = Messages::create([
					"client_id"		=> $this->_id,
					"dialogue_id"	=> $record->id,
					"text"			=> $post['text']
				]);
				
				$this->sendEmail(
					'new-appeal',
					null,
					array(
						'name'	=> $this->_user->name,
						'id'	=> $this->_user->id,
						'url'	=> url('/admin/chats/'.$record->id.'/edit')
					)
				);
				
				$status		= true;
				$msg		= '';
				$payload	= [
					"id"		=> $record->id,
					"theme"		=> $theme->label,
					"date"		=> date('Y-m-d')
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
	
	function message(Request $request){
		$this->session();
		
		$status = false;
		$errors = array();
		$msg	= trans('ajax.failed_send_message');
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
			$dialog = Dialogues::query()
								->where([
									"client_id"		=> $this->_id,
									"id"			=> $id
								])
								->first();
			
			if($dialog){
				$text = $request->get('text');
				
				$message = Messages::create([
					"client_id"		=> $this->_id,
					"dialogue_id"	=> $dialog->id,
					"text"			=> $text
				]);
				
				$date = date('d/m/Y');
				$time = date('H:i');
				
				$this->sendEmail(
					'new-message',
					null,
					array(
						'name'	=> $this->_user->name,
						'id'	=> $this->_user->id,
						'url'	=> url('/admin/chats/'.$dialog->id.'/edit'),
						'text'	=> $text
					)
				);
				
				$status		= true;
				$msg		= '';
				$payload	= [
					"author"	=> [
						"id"		=> $this->_user->id,
						"name"		=> $this->_user->name
					],
					"created"	=> [
						"date"		=> $date,
						"time"		=> $time
					],
					"text"		=> $text
				];
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
