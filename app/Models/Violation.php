<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

use App\Models\ViolationPhotos;

class Violation extends Model{
	
	protected $table	= 'violation';
	
	public $timestamps	= false;
	
	protected $fillable = [
		'client_id',
		'type',
		'address',
		'name'
	];
	
	public function photos(){
		return $this->hasMany(ViolationPhotos::class, 'record_id');
	}
}
