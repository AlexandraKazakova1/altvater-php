<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class ApplicationsPhotos extends Model{
	
	protected $table	= 'application-photos';
	
	public $timestamps	= false;
	
	protected $fillable = [
		'record_id',
		'image'
	];
}
