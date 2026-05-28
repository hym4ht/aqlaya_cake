<?php

namespace App\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Share route prefix with all views
        view()->composer('*', function ($view) {
            $routeName = request()->route()?->getName() ?? '';
            $routePrefix = str_starts_with($routeName, 'owner.') ? 'owner' : 'admin';
            $view->with('routePrefix', $routePrefix);
        });
    }
}
