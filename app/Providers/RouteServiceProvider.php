<?php

namespace App\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
    protected $namespace = 'App\Http\Controllers';

    public function map()
    {
        Route::middleware(['api', 'auth:api'])
            ->prefix('api/v1')
            ->name('api.')
            ->namespace($this->namespace.'\\Api')
            ->group(base_path('routes/api-routes.php'));


        Route::middleware('web')
            ->namespace($this->namespace)
            ->group(base_path('routes/web-routes.php'));


        Route::middleware(['web', 'auth'])
            ->name('user.')
            ->namespace($this->namespace.'\\User')
            ->group(base_path('routes/web-user-routes.php'));


        Route::middleware(['web', 'auth', 'is-admin'])
            ->prefix('admin')
            ->name('admin.')
            ->namespace($this->namespace.'\\Admin')
            ->group(base_path('routes/web-admin-routes.php'));
    }
}
