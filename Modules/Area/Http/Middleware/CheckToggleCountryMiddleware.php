<?php

namespace Modules\Area\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckToggleCountryMiddleware
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
        if (config('setting.other.toggle_supported_countries') != 1)
            return abort(404);
        return $next($request);
    }
}
