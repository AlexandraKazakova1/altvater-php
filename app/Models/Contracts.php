<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class Contracts extends Model{
	
	protected $table	= 'contracts';
	
	public $timestamps	= false;
	
	protected $fillable = [
		'client_id',
		'type',
		'container',
		'volume',
		'count_containers',
		'period',
		'address',
		'name',
		'phone',
		'email'
	];
}
