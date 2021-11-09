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
use App\Helpers\GeocodeHelper;

use App\Models\User;
use App\Models\UserAddresses;
use App\Models\UserAddressesImages;

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
		$msg	= trans('ajax.failed_add_address');
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
				'name'					=> 'required|string|min:2|max:150',
				'addresses'				=> 'required|string|min:2|max:200'
			),
			array(
				'name.required'			=> trans('ajax_validation.required'),
				'name.min'				=> trans('ajax_validation.min_length'),
				'name.max'				=> trans('ajax_validation.max_length'),
				
				'addresses.required'	=> trans('ajax_validation.required'),
				'addresses.min'			=> trans('ajax_validation.min_length'),
				'addresses.max'			=> trans('ajax_validation.max_length'),
			)
		);
		
		if($validator->passes()){
			$error	= false;
			
			if(!$error){
				$geo = new GeocodeHelper();
				$geo->setKey(env('GEOCODE_KEY'));
				$geo->address	= true;
				$geo->street	= true;
				$geo->house		= true;
				
				$result = $geo->query($post['addresses']);
				
				if(!$result){
					$error	= true;
					$msg	= trans('ajax.failed_add_address');
				}else{
					$addresses = [];
					
					if($result->street){
						$addresses[] = $result->street;
					}
					
					if($result->house){
						$addresses[] = $result->house;
					}
				}
			}
			
			if(!$error){
				$images	= [];
				$names	= [];
				
				$tmp_images	= $request->get('images');
				
				if($tmp_images && is_array($tmp_images)){
					foreach($tmp_images as $i => $item){
						if(isset($item['name']) && isset($item['mime']) && isset($item['data'])){
							$item['mime'] = explode('/', $item['mime']);
							
							if($item['mime'][0] == 'image'){
								$images[]	= base64_decode($item['data']);
								$names[]	= md5(time().'-'.$i.'-'.$item['mime'][1]).'.'.$item['mime'][1];
							}
						}
					}
				}
			}
			
			if(!$error){
				$urls = [];
				
				$record = UserAddresses::create([
					'client_id'			=> $this->_id,
					'name'				=> $post['name'],
					'address'			=> implode(', ', $addresses),
					'lat'				=> $result->lat,
					'lng'				=> $result->lng
				]);
				
				if($images){
					foreach($images as $i => $item){
						Storage::put('address-images/'.$names[$i], $fileContents);
						
						$urls[] = url('storage/address-images/'.$names[$i]);
						
						UserAddressesImages::create([
							'address_id'	=> $record->id,
							'file'			=> 'address-images/'.$names[$i]
						]);
					}
				}
				
				$status = true;
				$msg	= trans('ajax.success_add_address');
				
				$payload= [
					"id"		=> $record->id,
					"name" 		=> $record->name,
					"addresses"	=> $record->address,
					"lat"		=> $record->lat,
					"lng"		=> $record->lng,
					"images"	=> $urls
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
