<?php

namespace App\Http\Middleware;

use Closure;
use Session;

class CurrencySwitcher
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (!Session::has(env('SES_GLOBAL_CURRENCY')))
        {
           Session::put(env('SES_GLOBAL_CURRENCY'), '2');
        }
        return $next($request);
    }
}