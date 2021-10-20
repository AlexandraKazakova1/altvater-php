<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable	{
	
	use HasFactory, Notifiable;
	
	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
		'name',
		'surname',
		'middlename',
		'email',
		'email_verified_at',
		'password',
		'remember_token',
		'created_at',
		'updated_at',
		'phone',
		'extra_phone',
		'blocked',
		'type',
		'verify_email',
		'verify_phone',
		'email_token',
		'phone_token',
		'phone_code',
		'position',
		'addresses',
		'index'
	];
	
	/**
	 * The attributes that should be hidden for arrays.
	 *
	 * @var array
	 */
	protected $hidden = [
		'password',
		'remember_token',
	];
	
	/**
	 * The attributes that should be cast to native types.
	 *
	 * @var array
	 */
	protected $casts = [
		'email_verified_at' => 'datetime',
	];
}
