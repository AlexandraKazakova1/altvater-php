<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

use App\Models\UserAddressesImages;

class UserAddresses extends Model{
	
	protected $table	= 'user_addresses';
	
	public $timestamps	= false;
	
	protected $fillable = [
		'client_id',
		'name',
		'address',
		'lat',
		'lng'
	];
	
	public function images(){
		return $this->hasMany(UserAddressesImages::class, 'address_id');
	}
}
