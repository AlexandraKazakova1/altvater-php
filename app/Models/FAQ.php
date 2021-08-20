<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class FAQ extends Model {
	
	protected $table	= 'faq';
	
	public $timestamps	= false;
	
	protected $fillable = [
		'created_at',
		'updated_at',
		'public',
		'sort',
		'title',
		'text'
	];
}
