<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class Contacts extends Model{
	
	protected $table	= 'contacts';
	
	public $timestamps	= false;
	
	protected $fillable = [
		'page_id',
		'type',
		'public',
		'value'
	];
}
