<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

use App\Models\Clients;

class Acts extends Model{
	
	protected $table	= 'acts';
	
	public $timestamps	= false;
	
	protected $fillable = [
		'created_at',
		'updated_at',
		'date',
		'client_id',
		'number',
		'status',
		'file'
	];
	
	public function client(){
		return $this->belongsTo(Clients::class, 'client_id');
	}
}
