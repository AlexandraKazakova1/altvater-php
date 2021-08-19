<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

use App\Models\ServicesImages;

class Services extends Model {
	
	protected $table	= 'services';
	
	public $timestamps	= false;
	
	protected $fillable = [
		'created_at',
		'updated_at',
		'public',
		'slug',
		'title',
		'keywords',
		'description',
		'robots',
		'canonical',
		'header',
		'image',
		'slider_label',
		'text'
	];
	
	public function images(){
		return $this->hasMany(ServicesImages::class, 'service_id');
	}
}
