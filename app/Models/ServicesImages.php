<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class ServicesImages extends Model{
	
	protected $table	= 'services_images';
	
	public $timestamps	= false;
	
	protected $fillable = [
		'service_id',
		'image',
		'alt'
	];
}
