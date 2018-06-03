<?php

namespace App\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
    protected $namespace = 'App\Http\Controllers';

    public function map()
    {
        $this->mapApiRoutes();

        $this->mapWebRoutes();

        //
    }

    protected function mapWebRoutes()
    {
        Route::middleware('web')
             ->namespace($this->namespace)
             ->group(base_path('routes/web-routes.php'));
    }

    protected function mapApiRoutes()
    {
        Route::prefix('api/v1')
             ->middleware(['api', 'auth:api'])
             ->name('api.')
             ->namespace($this->namespace.'\\Api')
             ->group(base_path('routes/api-routes.php'));
    }
}
