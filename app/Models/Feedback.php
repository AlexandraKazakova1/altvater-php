<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class Feedback extends Model{
	
	protected $table	= 'feedback';
	
	public $timestamps	= false;
	
	protected $fillable = [
		'name',
		'email',
		'messasge'
	];
}
