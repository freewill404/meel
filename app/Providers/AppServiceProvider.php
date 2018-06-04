<?php

namespace App\Providers;

use App\Listeners\Observers\EmailScheduleObserver;
use App\Models\EmailSchedule;
use Illuminate\Support\ServiceProvider;
use Laravel\Passport\Passport;

class AppServiceProvider extends ServiceProvider
{
    public function boot()
    {
        EmailSchedule::observe(EmailScheduleObserver::class);
    }

    public function register()
    {
        Passport::ignoreMigrations();

        if (! $this->app->environment('production')) {
            $this->app->register(\Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider::class);
        }
    }
}
