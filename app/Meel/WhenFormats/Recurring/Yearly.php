<?php

namespace App\Meel\WhenFormats\Recurring;

use App\Support\DateTime\DateString;
use App\Support\DateTime\SecondlessTimeString;
use App\Support\Enums\Months;
use Carbon\Carbon;

class Yearly extends RecurringWhenFormat
{
    protected $intervalDescription = 'yearly';

    protected $dateOfTheMonth = 1;

    protected $monthOfTheYear = 'january';

    public function __construct(string $string)
    {
        $this->usableMatch = strpos($string, 'yearly') !== false;

        if (preg_match('/'.Months::regex().'/', $string, $matches)) {
            $this->monthOfTheYear = $matches[1];
        }

        if (preg_match('/(\d+)(st|nd|rd|th)/', $string, $matches)) {
            $date = (int) $matches[1];

            if ($date >= 1 && $date <= 31) {
                $this->dateOfTheMonth = $date;
            }
        }
    }

    public function getNextDate(SecondlessTimeString $setTime, $timezone = null): DateString
    {
        $setTimeIsLaterThanNow = $setTime->laterThanNow($timezone);

        $thisYear = Carbon::parse('this year '.$this->monthOfTheYear)->lastOfMonth();

        if ($thisYear->day >= $this->dateOfTheMonth) {
            $thisYear->day($this->dateOfTheMonth);
        }

        $thisYearDateString = new DateString($thisYear);

        if ($thisYearDateString->isAfterToday($timezone)) {
            return $thisYearDateString;
        }

        if ($thisYearDateString->isToday($timezone) && $setTimeIsLaterThanNow) {
            return $thisYearDateString;
        }

        $nextYear = Carbon::parse('next year '.$this->monthOfTheYear)->lastOfMonth();

        if ($nextYear->day >= $this->dateOfTheMonth) {
            $nextYear->day($this->dateOfTheMonth);
        }

        return new DateString($nextYear);
    }
}
