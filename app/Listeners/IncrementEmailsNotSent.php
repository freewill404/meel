<?php

namespace App\Listeners;

use App\Events\EmailNotSent;
use App\Models\SiteStats;

class IncrementEmailsNotSent
{
    public function handle(EmailNotSent $event)
    {
        SiteStats::incrementEmailsNotSent();

        $event->user->increment('emails_not_sent');
    }
}
