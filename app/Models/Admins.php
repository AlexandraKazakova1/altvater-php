<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class Admins extends Model {
	
	protected $table	= 'admin_users';
	
	public $timestamps	= false;
	
	protected $fillable = [
		'username',
		'password',
		'name',
		'avatar',
		'remember_token',
		'created_at',
		'updated_at'
	];
}
