<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Models\Ban;

class IsBanned
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
        if (Auth::user()) {
            $ban = Ban::where(['user_id' => Auth::user()->id, 'banned' => true])->first();
            if ($ban) {
                if (Route::currentRouteName() != "users.banned" and Route::currentRouteName() != "users.unban") {
                    return redirect('/banned');
                }
            }
        }

        return $next($request);
    }
}
