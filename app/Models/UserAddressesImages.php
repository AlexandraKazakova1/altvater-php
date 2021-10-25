<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class UserAddressesImages extends Model{
	
	protected $table	= 'user_addresses_images';
	
	public $timestamps	= false;
	
	protected $fillable = [
		'address_id',
		'file'
	];
}
