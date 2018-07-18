<?php

namespace App\Events;

use App\Models\Schedule;

class EmailNotSent extends BaseEvent
{
    public $schedule;

    public function __construct(Schedule $schedule)
    {
        $this->schedule = $schedule;
    }
}
