<?php

namespace Modules\Advertising\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckToggleAdvertisingMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (config('setting.other.toggle_advertising') != 1)
            return abort(404);
        return $next($request);
    }
}
