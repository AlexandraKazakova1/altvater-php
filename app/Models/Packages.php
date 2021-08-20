<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class Packages extends Model{
	
	protected $table	= 'packages';
	
	public $timestamps	= false;
	
	protected $fillable = [
		'public',
		'name',
		'sort',
		'price'
	];
}
