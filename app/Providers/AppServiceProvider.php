<?php

namespace App\Providers;

use App\Listeners\Observers\ScheduleObserver;
use App\Models\Schedule;
use Illuminate\Support\ServiceProvider;
use Laravel\Passport\Passport;

class AppServiceProvider extends ServiceProvider
{
    public function boot()
    {
        Schedule::observe(ScheduleObserver::class);
    }

    public function register()
    {
        Passport::ignoreMigrations();
    }
}
