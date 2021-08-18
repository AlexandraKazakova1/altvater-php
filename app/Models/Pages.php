<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class Pages extends Model {
	
	protected $table	= 'pages';
	
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
		'text'
	];
}
