<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class FooterMenu extends Model {
	
	protected $table	= 'footer-menu';
	
	public $timestamps	= false;
	
	protected $fillable = [
		'created_at',
		'updated_at',
		'public',
		'sort',
		'url',
		'title'
	];
}
