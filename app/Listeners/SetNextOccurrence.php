<?php

namespace App\Listeners;

use App\Events\EmailNotSent;
use App\Events\EmailSent;
use App\Meel\Schedules\ScheduleFormat;

class SetNextOccurrence
{
    /**
     * @param $event EmailSent|EmailNotSent
     */
    public function handle($event)
    {
        $schedule = new ScheduleFormat($event->schedule->when, $event->schedule->user->timezone);

        $event->schedule->update([
            'next_occurrence' => $schedule->isRecurring() ? $schedule->nextOccurrence() : null,
        ]);
    }
}
