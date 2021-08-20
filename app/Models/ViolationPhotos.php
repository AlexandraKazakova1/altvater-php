<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class ViolationPhotos extends Model{
	
	protected $table	= 'violation-photos';
	
	public $timestamps	= false;
	
	protected $fillable = [
		'record_id',
		'image'
	];
}
