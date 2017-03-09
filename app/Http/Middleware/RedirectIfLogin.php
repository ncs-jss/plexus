<?php

namespace App\Http\Middleware;

use Closure;
use Auth;
use Redirect;

class RedirectIfLogin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure                 $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (Auth::guard('user')->check() || Auth::guard('society')->check()) {
            return Redirect::back();
        }
        return $next($request);
    }
}
