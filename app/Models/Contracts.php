<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class Contracts extends Model{
	
	protected $table	= 'contracts';
	
	public $timestamps	= false;
	
	protected $fillable = [
		'created_at',
		'updated_at',
		'client_id',
		'address',
		'name',
		'contact',
		'phone',
		'extra_phone',
		'email',
		'index',
		'ipn',
		'edrpou',
		'file',
		'file_name',
		'date',
		'archive'
	];
}
