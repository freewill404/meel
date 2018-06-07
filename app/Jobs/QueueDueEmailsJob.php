<?php

namespace App\Jobs;

use App\Models\EmailSchedule;

class QueueDueEmailsJob extends BaseJob
{
    public function handle()
    {
        EmailSchedule::shouldBeSentNow()
            ->each(function (EmailSchedule $schedule) {
                $schedule->sendEmail();
            });
    }
}
