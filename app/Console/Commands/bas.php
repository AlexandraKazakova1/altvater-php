<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

use App\Helpers\StringHelper;

define('FILES_PATH', '/home/rh422094/altvater.kyiv.ua/www/storage/files');

class bas extends Command {
	
	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'command:bas';
	
	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Command bas';
	
	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct(){
		parent::__construct();
	}
	
	function ip(){
		$host		= \gethostname();
		
		return \gethostbyname($host);
	}
	
	function ftp(){
		$conn_id = ftp_connect(env('FTP_IP'));
		
		if (!$conn_id) {
			echo "Не удалось установить соединение с FTP-сервером!\n";
			return false;
		}
		
		$login_result = ftp_login($conn_id, env('FTP_LOGIN'), env('FTP_PASS'));
		
		if (!$login_result) {
			echo "Не удалось установить соединение с FTP-сервером!\n";
			return false;
		}
		
		$mode = ftp_pasv($conn_id, TRUE);
		
		if (!$mode) {
			echo "Не удалось установить режим работы с FTP-сервером!\n";
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
		
		$name = "post20211224115257.json";
		
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
						
						echo "Произведена запись в $name\n";
					}
					
					break;
				}
			}
		}
		
		fclose($handle);
		
		if(!$success){
			if(file_exists($dir.'/'.$name)){
				unlink($dir.'/'.$name);
			}
		}else{
			$docs = $this->download($conn_id, $files, $dir.'/'.$name, $dir);
			
			echo "\n";
			print_r($docs);
			echo "\n";
		}
		
		//
		
		ftp_close($conn_id);
	}
	
	function download($conn_id, $files, $file, $dir){
		$data = file_get_contents($file);
		$data = json_decode($data, true);
		
		$out = [];
		
		if($data){
			foreach($data as $item){
				$item['url'] = trim($item['url'], '/');
				$item['url'] = explode('/', $item['url'])[1];
				
				if(in_array($item['url'], $files)){
					if($item['сid']){
						$handle = fopen($dir.'/'.$item['url'], 'w');
						
						if (ftp_fget($conn_id, $handle, env('FTP_DIR').'/'.$item['url'], FTP_ASCII, 0)) {
							$out[] = $item;
						}
					}
				}
			}
		}
		
		return $out;
	}
	
	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function handle(){
		//$this->ip();
		$this->ftp();
	}
}
