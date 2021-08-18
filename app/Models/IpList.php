<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class IpList extends Model {
	
	protected $table	= 'ip_list';
	
	public $timestamps	= false;
	
	protected $fillable = [
		'ip'
	];
}
