<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class Themes extends Model {
	
	protected $table	= 'themes';
	
	public $timestamps	= false;
	
	protected $fillable = [
		'label'
	];
}
