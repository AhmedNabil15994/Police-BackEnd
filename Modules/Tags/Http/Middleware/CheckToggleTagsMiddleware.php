<?php

namespace Modules\Tags\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckToggleTagsMiddleware
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
        if (config('setting.other.toggle_tags') != 1)
            return abort(404);
        return $next($request);
    }
}
