<?php

namespace App\Listeners\Observers;

use App\Models\EmailSchedule;

class EmailScheduleObserver
{
    public function creating(EmailSchedule $emailSchedule)
    {
        $emailSchedule->next_occurrence = next_occurrence($emailSchedule);
    }

    public function created(EmailSchedule $emailSchedule)
    {
        $now = next_occurrence('now', $emailSchedule->user->timezone);

        if ($emailSchedule->next_occurrence == $now) {
            $emailSchedule->sendEmail();
        }
    }
}
