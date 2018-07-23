<?php

namespace App\Listeners;

use App\Events\EmailSent;
use App\Models\SiteStats;

class UpdateScheduleStats
{
    public function handle(EmailSent $event)
    {
        SiteStats::incrementEmailsSent();

        $event->schedule->user->increment('emails_sent');

        $event->schedule->update([
            'last_sent_at' => secondless_now(),
            'times_sent'   => $event->schedule->times_sent + 1,
        ]);
    }
}
