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
	
	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function handle(){
		$conn_id = ftp_ssl_connect(env('FTP_IP'));
		
		if (!$conn_id) {
			echo "Не удалось установить соединение с FTP-сервером!\n";
			return false;
		}
		
		$login_result = ftp_login($conn_id, env('FTP_LOGIN'), env('FTP_PASS'));
		
		if (!$login_result) {
			echo "Не удалось установить соединение с FTP-сервером!\n";
			return false;
		}
		
		$buff = ftp_rawlist($conn_id, '.');
		
		ftp_close($conn_id);
		
		print_r($buff);
		echo "\n";
	}
}
