<?php

namespace App\Meel\WhenFormats;

use App\Meel\TimeString;

class WeeklyNthDay extends RecurringWhenFormat
{
    protected $usableMatch = false;

    protected $nth;

    protected $day;

    public function __construct(string $string)
    {
        // Match:
        //   "every third saturday of the month"
        //   "the last saturday of the month"
        $this->usableMatch = preg_match('/(first|second|third|fourth|last) (monday|tuesday|wednesday|thursday|friday|saturday|sunday) of the month/', $string, $matches);

        if ($this->usableMatch) {
            $this->nth = $matches[1];

            $this->day = $matches[2];
        }
    }

    public function isUsableMatch(): bool
    {
        return (bool) $this->usableMatch;
    }

    public function getNextDate(TimeString $setTime, $timezone)
    {
        // TODO: !!!
    }
}
