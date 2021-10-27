<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class CalcObject extends Model{
	
	protected $table	= 'calc_object';
	
	public $timestamps	= false;
	
	protected $fillable = [
		'sort',
		'name',
		'label',
		'value'
	];
}
