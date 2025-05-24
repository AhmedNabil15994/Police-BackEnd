<?php

namespace App\Http\Middleware;

use Closure;

class LangApi
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $lang = $request->header("Accept-Language") ?? $request->header("lang");
        if ($lang) {
            app()->setLocale($lang);
        }
        return $next($request);
    }
}
