<?php

namespace App\Listeners\Observers;

use App\Models\EmailSchedule;
use App\Models\SiteStats;

class EmailScheduleObserver
{
    public function creating(EmailSchedule $emailSchedule)
    {
        $emailSchedule->next_occurrence = next_occurrence($emailSchedule);
    }

    public function created(EmailSchedule $emailSchedule)
    {
        SiteStats::incrementSchedulesCreated();

        $emailSchedule->user->increment('schedules_created');

        $now = next_occurrence('now', $emailSchedule->user->timezone);

        if ($emailSchedule->next_occurrence == $now) {
            $emailSchedule->sendEmail();
        }
    }
}
