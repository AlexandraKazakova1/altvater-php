<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class AddressesImages extends Model{
	
	protected $table	= 'addresses_images';
	
	public $timestamps	= false;
	
	protected $fillable = [
		'address_id',
		'file'
	];
}
