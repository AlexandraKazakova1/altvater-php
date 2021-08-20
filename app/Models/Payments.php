<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class Payments extends Model{
	
	protected $table	= 'payments';
	
	public $timestamps	= false;
	
	protected $fillable = [
		'name',
		'type',
		'client_id',
		'email',
		'package_id',
		'count_packages',
		'amount'
	];
}
