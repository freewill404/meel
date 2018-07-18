<?php

namespace App\Providers;

use App\Models\Schedule;
use App\Policies\SchedulePolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        Schedule::class => SchedulePolicy::class,
    ];

    public function boot()
    {
        $this->registerPolicies();
    }
}
