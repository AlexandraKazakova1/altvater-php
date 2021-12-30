<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;

use Illuminate\Support\Facades\Validator;

use App\Models\User;
use App\Models\Bills;
use App\Models\Acts;
use App\Models\Contracts;

use App\Helpers\StringHelper;
use App\Helpers\ImageHelper;

class BasController extends Controller {
	
	function ftp($name){
		$conn_id = ftp_connect(env('FTP_IP'));
		
		if (!$conn_id) {
			//echo "Не удалось установить соединение с FTP-сервером!\n";
			return false;
		}
		
		$login_result = ftp_login($conn_id, env('FTP_LOGIN'), env('FTP_PASS'));
		
		if (!$login_result) {
			//echo "Не удалось установить соединение с FTP-сервером!\n";
			return false;
		}
		
		$mode = ftp_pasv($conn_id, TRUE);
		
		if (!$mode) {
			//echo "Не удалось установить режим работы с FTP-сервером!\n";
			return false;
		}
		
		$ftp_rawlist = ftp_nlist($conn_id, env('FTP_DIR'));
		
		//
		
		if(!is_dir(FILES_PATH)){
			mkdir(FILES_PATH);
		}
		
		$time = time();
		
		$dir = FILES_PATH.'/'.$time;
		
		mkdir($dir);
		
		//$name = "post20211224115257.json";
		
		$handle = fopen($dir.'/'.$name, 'w');
		
		if(!$ftp_rawlist){
			$ftp_rawlist = [];
		}
		
		$success = false;
		
		$files = [];
		
		foreach ($ftp_rawlist as $item) {
			if($item != "." && $item != ".."){
				$item = explode('/', $item)[1];
				
				$files[] = $item;
				
				if($name == $item){
					if(ftp_fget($conn_id, $handle, env('FTP_DIR').'/'.$item, FTP_ASCII, 0)){
						$success = true;
					}
					
					break;
				}
			}
		}
		
		fclose($handle);
		
		$docs = [];
		
		if(!$success){
			if(file_exists($dir.'/'.$name)){
				unlink($dir.'/'.$name);
			}
			
			return false;
		}else{
			$docs = $this->download($conn_id, $files, $dir.'/'.$name, $dir);
		}
		
		//
		
		ftp_close($conn_id);
		
		return $docs;
	}
	
	function download($conn_id, $files, $file, $dir){
		$data = file_get_contents($file);
		$data = json_decode($data, true);
		
		$out = [];
		
		if($data){
			if(isset($data['сid'])){
				$item = $data;
				
				$item['url'] = trim($item['url'], '/');
				$item['url'] = explode('/', $item['url'])[1];
				
				if(in_array($item['url'], $files)){
					if($item['сid']){
						$handle = fopen($dir.'/'.$item['url'], 'w+');
						
						if (ftp_fget($conn_id, $handle, env('FTP_DIR').'/'.$item['url'], FTP_BINARY, 0)) {
							$item['dir'] = $dir;
							
							$out[] = $item;
						}
						
						fclose($handle);
					}
				}
			}else{
				foreach($data as $item){
					$item['url'] = trim($item['url'], '/');
					$item['url'] = explode('/', $item['url'])[1];
					
					if(in_array($item['url'], $files)){
						if($item['сid']){
							$handle = fopen($dir.'/'.$item['url'], 'w+');
							
							if (ftp_fget($conn_id, $handle, env('FTP_DIR').'/'.$item['url'], FTP_BINARY, 0)) {
								$item['dir'] = $dir;
								
								$out[] = $item;
							}
							
							fclose($handle);
						}
					}
				}
			}
		}
		
		return $out;
	}
	
	function sync(Request $request){
		$status		= false;
		$errors		= [];
		$payload	= [];
		$msg		= '';
		
		$name = $request->get('name');
		
		file_put_contents('/home/rh422094/altvater.kyiv.ua/www/storage/tmp/'.time(), print_r($request->all(), true));
		
		if($name){
			$result = $this->ftp($name);
			
			if($result){
				$dir = '/home/rh422094/altvater.kyiv.ua/www/storage/app/admin';
				
				foreach($result as $item){
					if($item['id']){
						$user = User::query()->where('uedrpou', $item['id'])->orWhere('ipn', $item['id'])->first();
						
						if($user){
							if($item['dt'] == 2){
								$record = Bills::query()->where('client_id', $user->id)->where('number', $item['nm'])->first();
								
								if(!$record){
									$record = Bills::create([
										'client_id'	=> $user->id,
										'number'	=> $item['nm'],
										'name'		=> $item['dn'].' '.$item['cn'],
										'date'		=> date("Y-m-d", $item['udt']),
										'file_name'	=> $item['url'],
										'file'		=> 'bills/'.$item['url'],
									]);
									
									if(file_exists($dir.'/bills/'.$item['url'])){
										unlink($dir.'/bills/'.$item['url']);
									}
									
									copy($item['dir'].'/'.$item['url'], $dir.'/bills/'.$item['url']);
								}else{
									$record->update([
										'file_name'	=> $item['url'],
										'file'		=> 'bills/'.$item['url'],
									]);
									
									if(file_exists($dir.'/bills/'.$item['url'])){
										unlink($dir.'/bills/'.$item['url']);
									}
									
									copy($item['dir'].'/'.$item['url'], $dir.'/bills/'.$item['url']);
								}
							}elseif($item['dt'] == 1){
								$record = Acts::query()->where('client_id', $user->id)->where('number', $item['nm'])->first();
								
								if(!$record){
									$record = Acts::create([
										'client_id'	=> $user->id,
										'number'	=> $item['nm'],
										'name'		=> $item['dn'].' '.$item['cn'],
										'date'		=> date("Y-m-d", $item['udt']),
										'file_name'	=> $item['url'],
										'file'		=> 'acts/'.$item['url'],
									]);
									
									if(file_exists($dir.'/acts/'.$item['url'])){
										unlink($dir.'/acts/'.$item['url']);
									}
									
									copy($item['dir'].'/'.$item['url'], $dir.'/acts/'.$item['url']);
								}else{
									$record->update([
										'file_name'	=> $item['url'],
										'file'		=> 'acts/'.$item['url'],
									]);
									
									if(file_exists($dir.'/acts/'.$item['url'])){
										unlink($dir.'/acts/'.$item['url']);
									}
									
									copy($item['dir'].'/'.$item['url'], $dir.'/acts/'.$item['url']);
								}
							}else{
								$record = Contracts::query()->where('client_id', $user->id)->where('number', $item['nm'])->first();
								
								if(!$record){
									$record = Contracts::create([
										'client_id'	=> $user->id,
										'number'	=> $item['nm'],
										'name'		=> $item['dn'].' '.$item['cn'],
										'date'		=> date("Y-m-d", $item['udt']),
										'file_name'	=> $item['url'],
										'file'		=> 'acts/'.$item['url'],
									]);
									
									if(file_exists($dir.'/contracts/'.$item['url'])){
										unlink($dir.'/contracts/'.$item['url']);
									}
									
									copy($item['dir'].'/'.$item['url'], $dir.'/contracts/'.$item['url']);
								}else{
									$record->update([
										'file_name'	=> $item['url'],
										'file'		=> 'contracts/'.$item['url'],
									]);
									
									if(file_exists($dir.'/contracts/'.$item['url'])){
										unlink($dir.'/contracts/'.$item['url']);
									}
									
									copy($item['dir'].'/'.$item['url'], $dir.'/contracts/'.$item['url']);
								}
							}
						}
					}
				}
				
				$status = true;
				$payload['name'] = $name;
			}
		}
		
		return array(
			'status'	=> $status,
			'msg'		=> $msg,
			'errors'	=> $errors,
			'payload'	=> $payload
		);
	}
	
}
