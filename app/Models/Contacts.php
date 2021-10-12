<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class Contacts extends Model{
	
	protected $table	= 'contacts';
	
	public $timestamps	= false;
	
	protected $fillable = [
		'page_id',
		'type',
		'public',
		'value',
		'label'
	];
	
	static function getData(){
		$tmp = DB::table('contacts')
					->where('public', 1)
					->select('type', 'value')
					->get();
		
		$data = [
			'address'	=> [],
			'phone'		=> [],
			'email'		=> [],
			'work'		=> [],
			'viber'		=> [],
			'telegram'	=> [],
		];
		
		foreach($tmp as $item){
			$item->value = trim($item->value);
			
			if($item->value){
				if($item->type == 'phone'){
					$item->value = preg_replace("/[^0-9]/", '', $item->value);
				}
			}
			
			if($item->value){
				$data[$item->type][] = $item;
			}
		}
		
		return $data;
	}
}
