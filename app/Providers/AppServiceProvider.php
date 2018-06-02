<?php

namespace App\Providers;

use App\Listeners\Observers\EmailScheduleObserver;
use App\Models\EmailSchedule;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function boot()
    {
        EmailSchedule::observe(EmailScheduleObserver::class);
    }

    public function register()
    {
        if (! $this->app->environment('production')) {
            $this->app->register(\Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider::class);
        }
    }
}
