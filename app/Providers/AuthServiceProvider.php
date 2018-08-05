<?php

namespace App\Providers;

use App\Models\Feed;
use App\Models\Schedule;
use App\Policies\FeedPolicy;
use App\Policies\SchedulePolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        Schedule::class => SchedulePolicy::class,
        Feed::class     => FeedPolicy::class,
    ];

    public function boot()
    {
        $this->registerPolicies();
    }
}
