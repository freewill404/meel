<?php

namespace App\Listeners;

use App\Events\EmailSent;
use App\Models\SiteStats;

class IncrementEmailsSent
{
    public function handle(EmailSent $event)
    {
        SiteStats::incrementEmailsSent();

        $event->emailSchedule->user->increment('emails_sent');
    }
}
