<?php

namespace App\Listeners;

use App\Events\ScheduledEmailSent;
use App\Models\SiteStats;

class UpdateScheduleStats
{
    public function handle(ScheduledEmailSent $event)
    {
        SiteStats::incrementEmailsSent();

        $event->schedule->user->increment('emails_sent');

        $event->schedule->update([
            'last_sent_at' => secondless_now(),
            'times_sent'   => $event->schedule->times_sent + 1,
        ]);
    }
}
