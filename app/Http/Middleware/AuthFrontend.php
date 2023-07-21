<?php

namespace App\Http\Middleware;

use Auth;
use Closure;

class AuthFrontend
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        if (! Auth::guard('frontend')->check()) {
            return redirect()->route('home_page');
        }
        return $next($request);
    }
}