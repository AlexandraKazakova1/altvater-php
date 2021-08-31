<?php

namespace App\Http\Middleware;

use Closure;

use Illuminate\Http\Request;

class Acl {
	
	/**
	 * The names of the cookies that should not be encrypted.
	 *
	 * @var array
	 */
	protected $except = [
		//
	];
	
	/**
	 * Handle the incoming request.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \Closure  $next
	 * @param  string  $role
	 * @return mixed
	 */
	public function handle(Request $request, Closure $next, $role){
		echo $request->ip();
		exit;
		
		$ip = \ip2long($request->ip());
		
		return $next($request);
	}
}
