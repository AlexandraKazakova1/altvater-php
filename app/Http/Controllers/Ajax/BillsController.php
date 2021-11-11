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
use App\Models\Bills;

class BillsController extends Controller {
	
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
	
	function index(Request $request){
		$this->session();
		
		$status = false;
		$errors = array();
		$msg	= '';
		$payload= [
			'html'	=> '',
			'count'	=> 0
		];
		
		$sort = $request->get('sort');
		
		if($sort != "date" && $sort != "number" && $sort != "status"){
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
		}elseif($sort == "status"){
			$column = 'paid';
		}
		
		$status = true;
		
		$payload['count'] = Bills::query()->where('client_id', $this->_id)->count();
		
		$offset = (int)$request->get('offset');
		$limit	= 4;
		
		if($offset < 0){
			$offset = 0;
		}else{
			if($offset > $payload['count']){
				$offset = $payload['count'];
			}
		}
		
		$data = Bills::query()->where('client_id', $this->_id)->skip($offset)->take($limit)->orderBy($column, 'desc')->get();
		
		$payload['html'] = view(
								'account.components.bills',
								[
									'bills'	=> $data
								]
							)
							->render();
		
		return response()->json([
			'status' 	=> $status,
			'message'	=> $msg,
			'errors'	=> $errors,
			'payload'	=> $payload
		], 200, [], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
	}
}
