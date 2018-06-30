<?php

namespace App\Listeners;

use App\Events\EmailNotSent;
use App\Events\EmailSent;
use App\Meel\EmailScheduleFormat;

class SetNextOccurrence
{
    /**
     * @param $event EmailSent|EmailNotSent
     */
    public function handle($event)
    {
        $schedule = new EmailScheduleFormat($event->emailSchedule->when, $event->emailSchedule->user->timezone);

        $event->emailSchedule->update([
            'next_occurrence' => $schedule->isRecurring() ? $schedule->nextOccurrence() : null,
        ]);
    }
}
