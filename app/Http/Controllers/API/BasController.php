<?php

namespace App\Http\Controllers\API;

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

class BasController extends Controller {
	
	function sync(Request $request){
		$status		= false;
		$errors		= [];
		$payload	= [];
		$msg		= '';
		
		$name = $request->get('name');
		
		if($name){
			$status = true;
			$payload['name'] = $name;
		}
		
		return array(
			'status'	=> $status,
			'msg'		=> $msg,
			'errors'	=> $errors,
			'payload'	=> $payload
		);
	}
	
}
