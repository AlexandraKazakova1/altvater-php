<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

use App\Models\NewsViews;

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
		'views',
		'text'
	];
	
	public function views(){
		return $this->hasMany(NewsViews::class, 'page_id');
	}
	
	static function getNew(){
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
	
	static function getLast(){
		$tmp = DB::table('news')
					->where('public', 1)
					->orderBy('created_at', 'desc')
					->select('created_at', 'slug', 'title', 'image')
					->first();
		
		if($tmp){
			$time = strtotime($tmp->created_at);
			
			$tmp->date = (object)[
				'd'	=> date('d', $time),
				'm'	=> date('m', $time),
				'y'	=> date('Y', $time),
			];
			
			return $tmp;
		}
		
		return [];
	}
	
	static function getPopular(){
		$tmp = DB::table('news')
					->where('public', 1)
					->whereRaw("`views` > 0")
					->orderBy('views', 'desc')
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
								
				$data[] = $item;
			}
		}
		
		return $data;
	}
	
	static function getOnce(){
		$tmp = DB::table('news')
					->where('public', 1)
					->orderBy('created_at', 'desc')
					->select('created_at', 'slug', 'title', 'image')
					->take(3)
					->get();
	}
}
