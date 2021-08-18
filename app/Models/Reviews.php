<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class Reviews extends Model {
	
	protected $table	= 'reviews';
	
	public $timestamps	= false;
	
	protected $fillable = [
		'created_at',
		'updated_at',
		'public',
		'image',
		'name',
		'text'
	];
}
