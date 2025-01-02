<?php

namespace App\Providers;

use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // Theme stuff
        View::composer('layouts.app', function ($view) {
            $theme = \Cookie::get('theme');
            $alert = Cache::get('alert');
            if(!in_array($theme, config('app.themes'))) {
                $theme = 'default'; 
            }
            
            $view->with(['theme' => $theme, 'alert' => $alert]);            
        });

        View::composer('layouts.admin', function ($view) {
            $theme = \Cookie::get('theme');
            $alert = Cache::get('alert');
            if(!in_array($theme, config('app.themes'))) {
                $theme = 'default'; 
            }
            
            $view->with(['theme' => $theme, 'alert' => $alert]);            
        });

        View::composer('layouts.mauer', function ($view) {
            $theme = \Cookie::get('theme');

            if(!in_array($theme, config('app.themes'))) {
                $theme = 'default'; 
            }
            
            $view->with(['theme' => $theme]);
        });

        Blade::if('admin', function(){
            return auth()->user()->isAdmin();
        });

        Response::macro('api', function ($value)
        {
            return Response::json($value)->header('Access-Control-Allow-Origin', '*')->header('Access-Control-Allow-Headers', '*');
        });

        // if this breaks any clients remove it
        if(config('app.env') == 'production')
        {
            URL::forceScheme('https');
        }
    }
}
