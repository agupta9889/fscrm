<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle(Request $request, Closure $next, $guard = null)
    {
        if (Auth::guard($guard)->guest()) {
            if ($request->ajax()) {
                return response('Unauthorized.', 401);
            } else {
                return redirect()->guest('login')->withSuccess('Oopps! You do not have access');

            }
        }
        if(Auth::guard($guard)->guest()==false && Auth::user()->role!=0 && Auth::user()->status!=0){
            Auth::logout();
            return redirect()->guest('login');
            
        }

        return $next($request);
    }
}
