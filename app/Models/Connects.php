<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

use App\Models\User;

class Connects extends Model{
	
	protected $table	= 'connects';
	
	public $timestamps	= false;
	
	protected $fillable = [
		'created_at',
		'updated_at',
		'client_id',
		'number',
		'name',
		'edrpou',
		'confirm'
	];
	
	public function client(){
		return $this->belongsTo(User::class, 'client_id');
	}
}
