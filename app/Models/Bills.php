<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

use App\Models\User;

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
		'paid',
		'name',
		'file',
		'file_name'
	];
	
	public function client(){
		return $this->belongsTo(User::class, 'client_id');
	}
}
