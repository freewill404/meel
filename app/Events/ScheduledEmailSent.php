<?php

namespace App\Events;

use App\Mail\Email;
use App\Models\Schedule;

class ScheduledEmailSent extends BaseEvent
{
    public $schedule;

    public $email;

    public function __construct(Schedule $schedule, Email $email)
    {
        $this->schedule = $schedule;

        $this->email = $email;
    }
}
