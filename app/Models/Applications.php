<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

use App\Models\ApplicationsPhotos;

class Applications extends Model{
	
	protected $table	= 'applications';
	
	public $timestamps	= false;
	
	protected $fillable = [
		'client_id',
		'type',
		'address',
		'name',
		'phone',
		'email',
		'service',
		'volume'
	];
	
	public function photos(){
		return $this->hasMany(ApplicationsPhotos::class, 'record_id');
	}
}
