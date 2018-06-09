<?php

namespace App\Listeners;

use App\Events\EmailSent;

class CreateEmailScheduleHistory
{
    public function handle(EmailSent $event)
    {
        $event->emailSchedule->emailScheduleHistories()->create([
            'sent_at' => secondless_now($event->emailSchedule->user->timezone),
        ]);
    }
}
