<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class NewsViews extends Model{
	
	protected $table	= 'news_views';
	
	public $timestamps	= false;
	
	protected $fillable = [
		'page_id',
		'ip'
	];
}
