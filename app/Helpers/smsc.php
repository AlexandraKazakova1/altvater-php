<?php

namespace App\Helpers;

use App\Helpers\Helper;
use DB;
use App\Helpers\CurlHelper;

/**
 * smsc
 */
class smsc extends Helper{
	
	private $username;
	private $password;
	
	private $from;
	
	public function config_user($username, $password){
		$this->username = $username;
		$this->password = $password;
		
		//$this->from = $from;
		
		return $this;
	}
	
	public function send($to, $data, $template){
		$text = DB::table('cms_email_templates')->where('slug', $template)->first();
		
		echo "\ntext:\n";
		print_r($text);
		echo "\n";
		
		if(is_null($text)) return false;
		
		$text = $text->content;
		
		foreach($data as $key => $value){
			$text = str_replace('{' . $key . '}', $value, $text);
		}
		
		//$text = urlencode(iconv('utf-8','windows-1251', $text));
		
		$url = 'https://smsc.ua/sys/send.php';
		
		CurlHelper::setUrl($url);
		CurlHelper::setTimeout(10);
		CurlHelper::setConnectTimeout(5);
		//CurlHelper::json(true);
		CurlHelper::post(true);
		
		$send = [
			'login' 			=> $this->username,
			'psw' 				=> $this->password,
			'phones' 			=> "+".$to,
			'sender'			=> env("SMSCRU_SENDER"),
			'mes'				=> $text
		];
		
		echo "\nsend:\n";
		print_r($send);
		echo "\n";
		
		CurlHelper::setData($send);
		
		$data = CurlHelper::request();
		
		echo "\ndata:\n";
		print_r($data);
		echo "\n";
		
		exit;
		
		if($data){
			$data = substr($data, 0, 2);
			
			return ($data == "OK");
		}
		
		return false;
	}
}
