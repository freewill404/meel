<?php

namespace App\Meel\Schedules\WhatFormats;

use App\Models\Schedule;

abstract class WhatFormat
{
    protected $schedule;

    protected $user;

    protected $timezone;

    public function __construct(Schedule $schedule)
    {
        $this->schedule = $schedule;

        $this->user = $schedule->user;

        $this->timezone = $this->user->timezone;
    }

    abstract public function applyFormat(string $string): string;
}
