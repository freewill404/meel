<?php

namespace App\Events;

use App\Models\EmailSchedule;

class EmailNotSent extends BaseEvent
{
    public $emailSchedule;

    public function __construct(EmailSchedule $emailSchedule)
    {
        $this->emailSchedule = $emailSchedule;
    }
}
