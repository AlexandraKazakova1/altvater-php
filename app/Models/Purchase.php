<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

use App\Models\Clients;

class Purchase extends Model {
	
	protected $table	= 'purchase';
	
	public $timestamps	= false;
	
	protected $fillable = [
		'client_id',
		'phone',
		'email',
		'name',
		'weight',
		'address',
		'type'
	];
	
	public function client(){
		return $this->belongsTo(Clients::class, 'client_id');
	}
}
