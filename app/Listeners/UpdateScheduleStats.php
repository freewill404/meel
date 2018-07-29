<?php

namespace App\Listeners;

use App\Events\ScheduledEmailNotSent;
use App\Events\ScheduledEmailSent;
use App\Models\SiteStats;

class UpdateScheduleStats
{
    /**
     * @param $event ScheduledEmailSent|ScheduledEmailNotSent
     */
    public function handle($event)
    {
        $event instanceof ScheduledEmailSent
            ? $this->sent($event)
            : $this->notSent($event);
    }

    protected function sent(ScheduledEmailSent $event)
    {
        SiteStats::incrementScheduledEmailsSent();

        $event->schedule->user->increment('scheduled_emails_sent');

        $event->schedule->update([
            'last_sent_at' => secondless_now(),
            'times_sent'   => $event->schedule->times_sent + 1,
        ]);
    }

    protected function notSent(ScheduledEmailNotSent $event)
    {
        SiteStats::incrementScheduledEmailsNotSent();

        $event->schedule->user->increment('scheduled_emails_not_sent');
    }
}
