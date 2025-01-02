<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RemoveExtraneousSlashes
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (str_contains($request->getRequestUri(), '//')) {
            return redirect(preg_replace('#/+#', '/', $request->getRequestUri()));
        }

        return $next($request);
    }
}
