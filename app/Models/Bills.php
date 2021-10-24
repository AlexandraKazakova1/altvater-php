<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

use App\Models\Clients;

class Bills extends Model{
	
	protected $table	= 'bills';
	
	public $timestamps	= false;
	
	protected $fillable = [
		'created_at',
		'updated_at',
		'date',
		'client_id',
		'number',
		'amount',
		'signed'
	];
	
	public function client(){
		return $this->belongsTo(Clients::class, 'client_id');
	}
}
