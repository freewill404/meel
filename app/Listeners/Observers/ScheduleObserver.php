<?php

namespace App\Listeners\Observers;

use App\Meel\When\ScheduleFormat;
use App\Models\Schedule;
use App\Models\SiteStats;
use Carbon\Carbon;
use RuntimeException;

class ScheduleObserver
{
    public function creating(Schedule $schedule)
    {
        $userTimezone = $schedule->user->timezone;

        $scheduleFormat = new ScheduleFormat(
            now($userTimezone), $schedule->when
        );

        $nextOccurrence = $scheduleFormat->nextOccurrence();

        if ($nextOccurrence === null) {
            throw new RuntimeException('Invalid "when": '.$schedule->when);
        }

        $nextOccurrence = (string) $nextOccurrence->changeTimezone($userTimezone, 'Europe/Amsterdam');

        $schedule->next_occurrence = Carbon::parse($nextOccurrence);
    }

    public function created(Schedule $schedule)
    {
        SiteStats::incrementEmailSchedulesCreated();

        $schedule->user->increment('email_schedules_created');

        if (secondless_now() == (string) $schedule->next_occurrence) {
            $schedule->sendEmail();
        }
    }
}
