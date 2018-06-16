<?php

namespace App\Providers;

use App\Models\EmailSchedule;
use App\Policies\EmailSchedulePolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        EmailSchedule::class => EmailSchedulePolicy::class,
    ];

    public function boot()
    {
        $this->registerPolicies();
    }
}
