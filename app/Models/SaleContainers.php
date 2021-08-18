<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

use App\Models\Clients;

class SaleContainers extends Model {
	
	protected $table	= 'sale_containers';
	
	public $timestamps	= false;
	
	protected $fillable = [
		'client_id',
		'value',
		'color',
		'count',
		'name',
		'phone',
		'email'
	];
	
	public function client(){
		return $this->belongsTo(Clients::class, 'client_id');
	}
}
