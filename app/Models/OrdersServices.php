<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class OrdersServices extends Model {
	
	protected $table	= 'orders_services';
	
	public $timestamps	= false;
	
	protected $fillable = [
		'created_at',
		'updated_at',
		'sort',
		'active',
		'name'
	];
}
