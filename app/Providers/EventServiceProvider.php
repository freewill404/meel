<?php

namespace App\Providers;

use App\Events\EmailSent;
use App\Listeners\CreateSentEmailHistory;
use App\Listeners\SendConfirmAccountEmail;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        Registered::class => [
            SendConfirmAccountEmail::class,
        ],

        EmailSent::class => [
            CreateSentEmailHistory::class,
        ],
    ];
}
