<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

use App\Models\User;
use App\Models\Admins;

class Messages extends Model{
	
	protected $table	= 'messages';
	
	public $timestamps	= false;
	
	protected $fillable = [
		'created_at',
		'updated_at',
		'dialogue_id',
		'client_id',
		'admin_id',
		'text',
		'read'
	];
	
	public function client(){
		return $this->belongsTo(User::class, 'client_id');
	}
	
	public function admin(){
		return $this->belongsTo(Admins::class, 'admin_id');
	}
}
