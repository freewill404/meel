<?php

namespace App\Providers;

use App\Events\EmailNotSent;
use App\Events\EmailSent;
use App\Listeners\CreateEmailScheduleHistory;
use App\Listeners\IncrementEmailsNotSent;
use App\Listeners\IncrementEmailsSent;
use App\Listeners\IncrementUsersRegistered;
use App\Listeners\SendConfirmAccountEmail;
use App\Listeners\SetNextOccurrence;
use App\Listeners\DecrementUserEmailsLeft;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        Registered::class => [
            SendConfirmAccountEmail::class,
            IncrementUsersRegistered::class,
        ],

        EmailSent::class => [
            SetNextOccurrence::class,
            CreateEmailScheduleHistory::class,
            IncrementEmailsSent::class,
            DecrementUserEmailsLeft::class,
        ],

        EmailNotSent::class => [
            SetNextOccurrence::class,
            IncrementEmailsNotSent::class,
        ],
    ];
}
