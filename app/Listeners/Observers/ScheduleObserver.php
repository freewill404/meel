<?php

namespace App\Listeners\Observers;

use App\Meel\Schedules\ScheduleFormat;
use App\Models\Schedule;
use App\Models\SiteStats;
use Carbon\Carbon;
use RuntimeException;

class ScheduleObserver
{
    public function creating(Schedule $schedule)
    {
        $scheduleFormat = new ScheduleFormat($schedule->when, $schedule->user->timezone);

        $nextOccurrence = $scheduleFormat->nextOccurrence();

        if ($nextOccurrence === null) {
            throw new RuntimeException('Invalid "when": '.$schedule->when);
        }

        $nextOccurrence = (string) $nextOccurrence->changeTimezone($schedule->user->timezone, 'Europe/Amsterdam');

        $schedule->next_occurrence = Carbon::parse($nextOccurrence);
    }

    public function created(Schedule $schedule)
    {
        SiteStats::incrementSchedulesCreated();

        $schedule->user->increment('schedules_created');

        if (secondless_now() == (string) $schedule->next_occurrence) {
            $schedule->sendEmail();
        }
    }
}
