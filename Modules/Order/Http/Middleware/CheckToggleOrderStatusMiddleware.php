<?php

namespace Modules\Order\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckToggleOrderStatusMiddleware
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
        if (config('setting.other.toggle_order_status') != 1)
            return abort(404);
        return $next($request);
    }
}
