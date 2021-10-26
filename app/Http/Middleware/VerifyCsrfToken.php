<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware {
	
	/**
	 * The URIs that should be excluded from CSRF verification.
	 *
	 * @var array
	 */
	protected $except = [
		'ajax/callback',
		'ajax/user/login',
		'ajax/user/register',
		'ajax/user/forgotten',
		'ajax/user/profile',
		'ajax/user/activation',
		'*'
	];
}
