<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The path to the "home" route for your application.
     *
     * This is used by Laravel authentication to redirect users after login.
     *
     * @var string
     */
    public const HOME = '/my/dashboard';

    /**
     * The controller namespace for the application.
     *
     * When present, controller route declarations will automatically be prefixed with this namespace.
     *
     * @var string|null
     */
    // protected $namespace = 'App\\Http\\Controllers';

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot()
    {
        $this->configureRateLimiting();

        $this->routes(function () {
            if (config('app.die_mauer')) {
                Route::domain(sprintf('mauer.%s', config('app.hostname')))
                    ->middleware('web')
                    ->namespace($this->namespace)
                    ->group(base_path('routes/mauer.php'));
            }

            Route::domain(sprintf('api.%s', config('app.hostname')))
                ->middleware('api')
                ->namespace($this->namespace)
                ->group(base_path('routes/api.php'));
            
            Route::domain(sprintf('api.%s', config('app.hostname')))
                ->middleware('api')
                ->namespace($this->namespace)
                ->group(base_path('routes/web.php'));
            
            Route::domain(sprintf('cdn.%s', config('app.hostname')))
                ->middleware('web')
                ->namespace($this->namespace)
                ->group(base_path('routes/cdn.php'));
            
            Route::middleware('roblox')
                ->namespace($this->namespace)
                ->group(base_path('routes/roblox.php'));
            
            Route::domain(sprintf('assetgame.%s', config('app.hostname')))
                ->middleware('web')
                ->namespace($this->namespace)
                ->group(base_path('routes/web.php'));

            Route::domain(sprintf('www.%s', config('app.hostname')))
                ->middleware('web')
                ->namespace($this->namespace)
                ->group(base_path('routes/web.php'));
            
            Route::domain(config('app.hostname'))
                ->middleware('web')
                ->namespace($this->namespace)
                ->group(base_path('routes/web.php'));
            
            // also import the API routes
            Route::domain(config('app.hostname'))
                ->middleware('api')
                ->namespace($this->namespace)
                ->prefix('api')
                ->group(base_path('routes/api.php'));
        });
    }

    /**
     * Configure the rate limiters for the application.
     *
     * @return void
     */
    protected function configureRateLimiting()
    {
        //
    }
}
