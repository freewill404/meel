<?php

namespace App\Listeners;

use App\Events\EmailSent;

class CreateSentEmailHistory
{
    public function handle(EmailSent $event)
    {
        $event->emailSchedule->emailScheduleHistories()->create([
            'sent_at'             => now($event->emailSchedule->user->timezone),
            'sent_at_server_time' => now(),
        ]);
    }
}
