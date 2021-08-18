<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

use App\Models\Clients;

class Reviews extends Model {
	
	protected $table	= 'reviews';
	
	public $timestamps	= false;
	
	protected $fillable = [
		'client_id',
		'rating',
		'text'
	];
	
	public function client(){
		return $this->belongsTo(Clients::class, 'client_id');
	}
}
