<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Actions extends Model {
	
	protected $table	= 'actions';
	
	public $timestamps	= false;
	
	protected $fillable = [
		'client_id',
		'tracking',
		'id'
	];
}
