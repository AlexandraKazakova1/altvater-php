<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

use App\Models\Contacts;

class Pages extends Model {
	
	protected $table	= 'pages';
	
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
		'subheader',
		'show_btn',
		'btn_label',
		'btn_url',
		'btn_class',
		'text',
		'about_header',
		'about_left_header',
		'about_left_image',
		'about_left_public',
		'about_left',
		'about_right_header',
		'about_right_image',
		'about_right_public',
		'about_right',
		'meta_public',
		'meta_header',
		'meta_text',
		'meta_image',
		'indicators_public',
		'indicators_text'
	];
	
	public function contacts(){
		return $this->hasMany(Contacts::class, 'page_id');
	}
}
