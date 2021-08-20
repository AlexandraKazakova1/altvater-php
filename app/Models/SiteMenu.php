<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class SiteMenu extends Model {
	
	protected $table	= 'menu';
	
	public $timestamps	= false;
	
	protected $fillable = [
		'created_at',
		'updated_at',
		'public',
		'sort',
		'url',
		'title',
		'class'
	];
}
