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
		
		$status		= true;
		$msg		= '';
		$payload	= [
			"id"		=> 1,
			"theme"		=> "test test",
			"date"		=> "24.10.2021"
		];
		
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
		
		$id = (int)$request->route('id');
		
		$status		= true;
		$msg		= '';
		$payload	= [
			"author"	=> [
				"id"		=> 1,
				"name"		=> "Ivan",
			],
			"created"	=> [
				"date"		=> "25/07/2020",
				"time"		=> "16:53",
			],
			"text"		=> "text text text text text text"
		];
		
		return response()->json([
			'status' 	=> $status,
			'message'	=> $msg,
			'errors'	=> $errors,
			'payload'	=> $payload
		], 200, [], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
	}
}
