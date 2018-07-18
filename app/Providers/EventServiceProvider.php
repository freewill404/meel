<?php

namespace App\Providers;

use App\Events\EmailNotSent;
use App\Events\EmailSent;
use App\Events\UserAlmostOutOfEmails;
use App\Events\UserOutOfEmails;
use App\Listeners\CreateScheduleHistory;
use App\Listeners\IncrementEmailsNotSent;
use App\Listeners\IncrementEmailsSent;
use App\Listeners\IncrementUsersRegistered;
use App\Listeners\SendAlmostOutOfEmailsEmail;
use App\Listeners\SendConfirmAccountEmail;
use App\Listeners\SendOutOfEmailsEmail;
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
            CreateScheduleHistory::class,
            IncrementEmailsSent::class,
            DecrementUserEmailsLeft::class,
        ],

        EmailNotSent::class => [
            SetNextOccurrence::class,
            IncrementEmailsNotSent::class,
        ],

        UserAlmostOutOfEmails::class => [
            SendAlmostOutOfEmailsEmail::class,
        ],

        UserOutOfEmails::class => [
            SendOutOfEmailsEmail::class,
        ],
    ];
}
