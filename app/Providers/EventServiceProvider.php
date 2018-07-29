<?php

namespace App\Providers;

use App\Events\EmailNotSent;
use App\Events\EmailSent;
use App\Events\Feeds\FeedEmailNotSent;
use App\Events\Feeds\FeedEmailSent;
use App\Events\ScheduledEmailNotSent;
use App\Events\ScheduledEmailSent;
use App\Events\Feeds\FeedCreating;
use App\Events\Feeds\FeedNotPolled;
use App\Events\Feeds\FeedPolled;
use App\Events\Feeds\FeedPollFailed;
use App\Events\UserAlmostOutOfEmails;
use App\Events\UserOutOfEmails;
use App\Listeners\Feeds\SendNewFeedEntryEmails;
use App\Listeners\Feeds\SetNextPollAt;
use App\Listeners\Feeds\UpdateFeedPollStats;
use App\Listeners\Feeds\UpdateFeedStats;
use App\Listeners\UpdateEmailStats;
use App\Listeners\UpdateScheduleStats;
use App\Listeners\IncrementUsersRegistered;
use App\Listeners\SendAlmostOutOfEmailsEmail;
use App\Listeners\SendConfirmAccountEmail;
use App\Listeners\SendOutOfEmailsEmail;
use App\Listeners\SetNextOccurrence;
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
            UpdateEmailStats::class,
        ],

        EmailNotSent::class => [
            UpdateEmailStats::class,
        ],

        ScheduledEmailSent::class => [
            SetNextOccurrence::class,
            UpdateScheduleStats::class,
        ],

        ScheduledEmailNotSent::class => [
            SetNextOccurrence::class,
            UpdateScheduleStats::class,
        ],

        UserAlmostOutOfEmails::class => [
            SendAlmostOutOfEmailsEmail::class,
        ],

        UserOutOfEmails::class => [
            SendOutOfEmailsEmail::class,
        ],

        FeedCreating::class => [
            SetNextPollAt::class,
        ],

        FeedNotPolled::class => [
            SetNextPollAt::class,
        ],

        FeedPolled::class => [
            UpdateFeedPollStats::class,
            SendNewFeedEntryEmails::class,
            SetNextPollAt::class,
        ],

        FeedPollFailed::class => [
            UpdateFeedPollStats::class,
            SetNextPollAt::class,
        ],

        FeedEmailSent::class => [
            UpdateFeedStats::class,
        ],

        FeedEmailNotSent::class => [
            UpdateFeedStats::class,
        ],

    ];
}
