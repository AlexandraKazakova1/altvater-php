<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

use App\Models\Messages;
use App\Models\User;
use App\Models\Themes;
use App\Models\Contracts;

class Dialogues extends Model{
	
	protected $table	= 'dialogues';
	
	public $timestamps	= false;
	
	protected $fillable = [
		'created_at',
		'updated_at',
		'client_id',
		'theme_id',
		'contract_id',
		'phone',
		'header',
		'file',
		'answer'
	];
	
	public function client(){
		return $this->belongsTo(User::class, 'client_id');
	}
	
	public function theme(){
		return $this->belongsTo(Themes::class, 'theme_id');
	}
	
	public function contract(){
		return $this->belongsTo(Contracts::class, 'contract_id');
	}
	
	public function messages(){
		return $this->hasMany(Messages::class, 'dialogue_id')->orderBy('created_at', 'asc');
	}
}
