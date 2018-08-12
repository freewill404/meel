<?php

namespace App\Providers;

use App\Listeners\Observers\FeedObserver;
use App\Listeners\Observers\ScheduleObserver;
use App\Models\Feed;
use App\Models\Schedule;
use GuzzleHttp\Client as Guzzle;
use Illuminate\Support\ServiceProvider;
use Laravel\Passport\Passport;

class AppServiceProvider extends ServiceProvider
{
    public function boot()
    {
        Schedule::observe(ScheduleObserver::class);

        Feed::observe(FeedObserver::class);
    }

    public function register()
    {
        Passport::ignoreMigrations();

        $this->app->bind(Guzzle::class, function () {
            return new Guzzle(['timeout' => 5, 'connect_timeout' => 5]);
        });
    }
}
