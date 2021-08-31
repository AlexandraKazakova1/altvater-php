<?php

namespace App\Http\Middleware;

use Closure;

use Illuminate\Http\Request;

use App\Models\IpList;

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
	public function handle(Request $request, Closure $next){
		$ip = \ip2long($request->ip());
		
		if(!$ip){
			return abort(404);
		}
		
		$check = IpList::query()->where('ip', $ip)->first();
		
		if(!$check){
			return abort(404);
		}
		
		return $next($request);
	}
}
