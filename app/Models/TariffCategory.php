<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class TariffCategory extends Model{
	
	protected $table	= 'tariff_category';
	
	public $timestamps	= false;
	
	protected $fillable = [
		'sort',
		'value',
		'name'
	];
}
