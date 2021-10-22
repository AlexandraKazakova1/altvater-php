<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class Messages extends Model{
	
	protected $table	= 'messages';
	
	public $timestamps	= false;
	
	protected $fillable = [
		'created_at',
		'updated_at',
		'dialogue_id',
		'client_id',
		'admin_id',
		'text'
	];
}
