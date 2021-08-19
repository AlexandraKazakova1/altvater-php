<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class News extends Model {
	
	protected $table	= 'news';
	
	public $timestamps	= false;
	
	protected $fillable = [
		'created_at',
		'updated_at',
		'public',
		'slug',
		'title',
		'keywords',
		'description',
		'robots',
		'canonical',
		'header',
		'image',
		'text'
	];
	
	static function getLast(){
		$tmp = DB::table('news')
					->where('public', 1)
					->orderBy('created_at', 'desc')
					->select('created_at', 'slug', 'title', 'image')
					->take(3)
					->get();
		
		$data = [];
		
		if(count($tmp)){
			$tmp = $tmp->toArray();
			
			foreach($tmp as $item){
				$item = (object)$item;
				
				$time = strtotime($item->created_at);
				
				$item->date = (object)[
					'd'	=> date('d', $time),
					'm'	=> date('m', $time),
					'y'	=> date('Y', $time),
				];
				
				if(mb_strlen($item->title) > 67){
					$item->title = mb_substr($item->title, 0, 67).'...';
				}
				
				$data[] = $item;
			}
		}
		
		return $data;
	}
}
