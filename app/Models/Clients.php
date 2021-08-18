<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class Clients extends Model{
	
	protected $table	= 'clients';
	
	public $timestamps	= false;
	
	protected $fillable = [
		'name',
		'phone',
		'address',
		'email',
		'blocked',
		'viber_id'
	];
}
