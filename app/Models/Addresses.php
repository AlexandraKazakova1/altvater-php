<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

use App\Models\AddressesImages;

class Addresses extends Model{
	
	protected $table	= 'addresses';
	
	public $timestamps	= false;
	
	protected $fillable = [
		'client_id',
		'name',
		'address'
	];
	
	public function images(){
		return $this->hasMany(AddressesImages::class, 'address_id');
	}
}
