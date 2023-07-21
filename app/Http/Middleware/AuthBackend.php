<?php

namespace App\Http\Middleware;

use Auth;
use Closure;
use Session;

class AuthBackend
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
        if (! Auth::guard('backend')->check()) {
            return redirect()->route('control.login');
        }else{
            if(Session::get(env('SES_BACKEND')) == null){
                Auth::guard('backend')->logout();
                Session::flush();
                return redirect()->route('control.login');
            }
        }
        return $next($request);
    }
}