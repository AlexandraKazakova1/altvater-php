<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;

use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;

use Mail;

class Controller extends BaseController {
	
	use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
	
	function sendEmail($key, $to = null, $file = null){
		$template = DB::table('email_templates')->where('slug', $key)->first();
		
		if(!$template){
			return false;
		}
		
		$subject	= $template->subject;
		$text		= $template->content;
		
		$config = [
			'appname'			=> '', 
			'email'				=> '', 
			'email_for_letters'	=> ''
		];
		
		$tmp_email = DB::table('admin_config')->whereIn('name', ['appname', 'email', 'email_for_letters'])->select('name', 'value')->get();
		
		foreach($tmp_email as $item){
			$item->value = trim($item->value);
			
			$config[$item->name] = $item->value;
		}
		
		if($template->emails){
			$template->emails = trim($template->emails);
			$to = explode(',', $template->emails);
		}
		
		if($to && is_string($to)){
			Mail::send(
				'emails.raw',
				array(
					'content' => $text
				),
				function($message) use ($config, $to, $text, $subject, $file){
					$message->from($config['email'], $config['appname']);
					
					$message->subject($subject);
					
					$message->to($to);
					
					if($file){
						$message->attach(
							$file, 
							[
								'as'	=> basename($file),
							]
						);
					}
				}
			);
		}else{
			if($to && is_array($to)){
				//
			}else{
				$config['email_for_letters'] = trim($config['email_for_letters']);
				$to = explode(',', $config['email_for_letters']);
			}
			
			foreach($to as $item){
				$item = trim($item);
				
				if($item){
					Mail::send(
						'emails.raw',
						array(
							'content' => $text
						),
						function($message) use ($config, $item, $text, $subject, $file){
							$message->from($config['email'], $config['appname']);
							
							$message->subject($subject);
							
							$message->to($item);
							
							if($file){
								$message->attach(
									$file, 
									[
										'as'	=> basename($file),
									]
								);
							}
						}
					);
				}
			}
		}
	}
}
