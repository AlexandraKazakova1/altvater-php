<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

use App\Helpers\StringHelper;

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
		
		echo "host:\n";
		echo $host;
		echo "\n";
		echo "\n";
		
		$ip_server	= \gethostbyname($host);
		
		echo "Server IP Address is:\n";
		echo $ip_server;
		echo "\n";
		echo "\n";
	}
	
	function ftp(){
		$conn_id = ftp_connect(env('FTP_IP'));
		
		echo "conn_id:\n";
		print_r($conn_id);
		echo "\n";
		
		if (!$conn_id) {
			echo "Не удалось установить соединение с FTP-сервером!\n";
			return false;
		}
		
		$login_result = ftp_login($conn_id, env('FTP_LOGIN'), env('FTP_PASS'));
		
		echo "login_result:\n";
		print_r($login_result);
		echo "\n";
		
		if (!$login_result) {
			echo "Не удалось установить соединение с FTP-сервером!\n";
			return false;
		}
		
		$buff = ftp_rawlist($conn_id, '.');
		
		ftp_close($conn_id);
		
		print_r($buff);
		echo "\n";
	}
	
	function sftp(){
		$conn_id = ssh2_connect(env('FTP_IP'), 22);
		
		echo "conn_id:\n";
		print_r($conn_id);
		echo "\n";
		
		if (!$conn_id) {
			echo "Не удалось установить соединение с сервером!\n";
			return false;
		}
		
		$login_result = ssh2_auth_password($conn_id, env('FTP_LOGIN'), env('FTP_PASS'));
		
		echo "login_result:\n";
		print_r($login_result);
		echo "\n";
		
		if (!$login_result) {
			echo "Не удалось пройти авторизацию на сервере!\n";
			return false;
		}
		
		$sftp = ssh2_sftp($conn_id);
		
		if (!$sftp) {
			echo "Could not initialize SFTP subsystem.\n";
			return false;
		}
		
		//fclose($stream);
	}
	
	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function handle(){
		$this->ip();
		//$this->ftp();
	}
}
