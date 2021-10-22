<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

use App\Models\ViolationPhotos;

class Dialogues extends Model{
	
	protected $table	= 'dialogues';
	
	public $timestamps	= false;
	
	protected $fillable = [
		'created_at',
		'updated_at',
		'client_id',
		'theme',
		'number',
		'phone',
		'header'
	];
	
	public function messages(){
		return $this->hasMany(Messages::class, 'dialogue_id');
	}
}
