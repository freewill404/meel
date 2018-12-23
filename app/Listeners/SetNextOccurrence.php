<?php

namespace App\Listeners;

use App\Events\ScheduledEmailNotSent;
use App\Events\ScheduledEmailSent;
use App\Meel\When\ScheduleFormat;

class SetNextOccurrence
{
    /**
     * @param $event ScheduledEmailSent|ScheduledEmailNotSent
     */
    public function handle($event)
    {
        $userTimezone = $event->schedule->user->timezone;

        $schedule = new ScheduleFormat(
            now($userTimezone),
            $event->schedule->when
        );

        $nextOccurrence = $schedule->recurring()
            ? $schedule->nextOccurrence()->changeTimezone($userTimezone, 'Europe/Amsterdam')
            : null;

        $event->schedule->update([
            'next_occurrence' => $nextOccurrence,
        ]);
    }
}
