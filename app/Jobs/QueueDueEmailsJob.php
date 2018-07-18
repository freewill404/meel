<?php

namespace App\Jobs;

use App\Models\Schedule;

class QueueDueEmailsJob extends BaseJob
{
    public function handle()
    {
        Schedule::shouldBeSentNow()
            ->each(function (Schedule $schedule) {
                $schedule->sendEmail();
            });
    }
}
