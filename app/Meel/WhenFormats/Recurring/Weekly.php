<?php

namespace App\Meel\WhenFormats\Recurring;

use App\Support\DateTime\DateString;
use App\Support\DateTime\SecondlessTimeString;
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

        if (preg_match('/'.Days::regex().'/', $string, $matches)) {
            $this->day = $matches[1];
        }
    }

    public function isUsableMatch(): bool
    {
        return $this->usableMatch;
    }

    public function getNextDate(SecondlessTimeString $setTime, $timezone = null): DateString
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
