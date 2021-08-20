<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class Contents extends Model{
	
	protected $table	= 'contents';
	
	public $timestamps	= false;
	
	protected $fillable = [
		'title',
		'text'
	];
}
