<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

use App\Models\OrdersServices;
use App\Models\User;

class Orders extends Model{
	
	protected $table	= 'orders';
	
	public $timestamps	= false;
	
	protected $fillable = [
		'created_at',
		'updated_at',
		'client_id',
		'service_id',
		'status',
		'date',
		'time',
		'addresses',
		'comment'
	];
	
	public function client(){
		return $this->belongsTo(User::class, 'client_id');
	}
	
	public function service(){
		return $this->belongsTo(OrdersServices::class, 'service_id');
	}
}
