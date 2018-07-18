<?php

namespace App\Listeners\Observers;

use App\Models\Schedule;
use App\Models\SiteStats;

class ScheduleObserver
{
    public function creating(Schedule $schedule)
    {
        $schedule->next_occurrence = next_occurrence($schedule);
    }

    public function created(Schedule $schedule)
    {
        SiteStats::incrementSchedulesCreated();

        $schedule->user->increment('schedules_created');

        $now = next_occurrence('now', $schedule->user->timezone);

        if ($schedule->next_occurrence == $now) {
            $schedule->sendEmail();
        }
    }
}
