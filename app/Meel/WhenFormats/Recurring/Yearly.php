<?php

namespace App\Meel\WhenFormats\Recurring;

use App\Meel\DateTime\DateString;
use App\Meel\DateTime\TimeString;
use Carbon\Carbon;

class Yearly extends RecurringWhenFormat
{
    protected $usableMatch = false;

    protected $dateOfTheMonth = 1;

    protected $monthOfTheYear = 'january';

    public function __construct(string $string)
    {
        $this->usableMatch = strpos($string, 'yearly') !== false;

        if (preg_match('/(january|february|march|april|may|june|july|august|september|october|november|december)/', $string, $matches)) {
            $this->monthOfTheYear = $matches[1];
        }

        if (preg_match('/(\d+)(st|nd|rd|th)/', $string, $matches)) {
            $date = (int) $matches[1];

            if ($date >= 1 && $date <= 31) {
                $this->dateOfTheMonth = $date;
            }
        }
    }

    public function isUsableMatch(): bool
    {
        return $this->usableMatch;
    }

    public function getNextDate(TimeString $setTime, $timezone): DateString
    {
        $setTimeIsEarlierThanNow = $setTime->earlierThanNow($timezone);

        $thisYear = Carbon::parse('this year '.$this->monthOfTheYear)->lastOfMonth();

        if ($thisYear->day >= $this->dateOfTheMonth) {
            $thisYear->day($this->dateOfTheMonth);
        }

        $thisYearDateString = new DateString($thisYear);

        if ($thisYearDateString->isAfterToday($timezone)) {
            return $thisYearDateString;
        }

        if ($thisYearDateString->isToday($timezone) && $setTimeIsEarlierThanNow) {
            return $thisYearDateString;
        }

        $nextYear = Carbon::parse('next year '.$this->monthOfTheYear)->lastOfMonth();

        if ($nextYear->day >= $this->dateOfTheMonth) {
            $nextYear->day($this->dateOfTheMonth);
        }

        return new DateString($nextYear);
    }
}
