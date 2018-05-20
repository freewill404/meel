<?php

namespace App\Meel\WhenFormats\Recurring;

use App\Meel\DateTime\DateString;
use App\Meel\DateTime\TimeString;
use App\Support\Enums\Days;
use Carbon\Carbon;

class Weekly extends RecurringWhenFormat
{
    protected $usableMatch = false;

    protected $day;

    public function __construct(string $string)
    {
        $this->usableMatch = strpos($string, 'weekly') !== false;

        $this->day = Days::MONDAY;

        if (preg_match('/(monday|tuesday|wednesday|thursday|friday|saturday|sunday)/', $string, $matches)) {
            $this->day = $matches[1];
        }
    }

    public function isUsableMatch(): bool
    {
        return $this->usableMatch;
    }

    public function getNextDate(TimeString $setTime, $timezone = null): DateString
    {
        $setTimeIsLaterThanNow = $setTime->laterThanNow($timezone);

        $weeklyOnDay = new DateString(
            Carbon::parse('this week '.$this->day, $timezone)
        );

        if ($weeklyOnDay->isAfterToday($timezone)) {
            return $weeklyOnDay;
        }

        if ($weeklyOnDay->isToday($timezone) && $setTimeIsLaterThanNow) {
            return $weeklyOnDay;
        }

        return new DateString(
            Carbon::parse('next week '.$this->day, $timezone)
        );
    }

    public function intervalDescription()
    {
        return 'weekly';
    }
}
