<?php

namespace App\Meel\Schedules\WhatFormats;

use App\Models\EmailSchedule;

abstract class WhatFormat
{
    protected $emailSchedule;

    protected $user;

    protected $timezone;

    public function __construct(EmailSchedule $emailSchedule)
    {
        $this->emailSchedule = $emailSchedule;

        $this->user = $emailSchedule->user;

        $this->timezone = $this->user->timezone;
    }

    abstract public function applyFormat(string $string): string;
}
