<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Str;

class RobloxAgent
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
        if (!str_contains(Str::lower($request->header('User-Agent')), 'roblox'))
        {
            abort(404);
        }

        return $next($request);
    }
}