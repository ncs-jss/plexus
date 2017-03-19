<?php

namespace App\Http\Middleware;

use Closure;
use Auth;
use Redirect;

class Society
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure                 $next
     * @param  guard name               $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = 'society')
    {
        if (Auth::guard($guard)->check()) {
            return $next($request);
        } elseif (Auth::guard('user')->check()) {
            return Redirect::back();
        }
        return Redirect::to('/society/login');
    }
}
