<?php

namespace App\Listeners;

use App\Events\EmailSent;

class CreateScheduleHistory
{
    public function handle(EmailSent $event)
    {
        $event->schedule->scheduleHistories()->create([
            'sent_at' => secondless_now($event->schedule->user->timezone),
        ]);
    }
}
