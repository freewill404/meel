<?php

namespace App\Jobs;

use App\Models\EmailSchedule;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendScheduledEmailJob extends BaseJob implements ShouldQueue
{
    protected $schedule;

    public function __construct(EmailSchedule $schedule)
    {
        $this->schedule = $schedule;
    }

    public function handle()
    {
        $this->schedule->sendEmail();
    }
}
