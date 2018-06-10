<?php

namespace App\Meel\WhenFormats\Recurring;

use App\Support\DateTime\DateString;
use App\Support\DateTime\SecondlessTimeString;

class Monthly extends RecurringWhenFormat
{
    protected $intervalDescription = 'monthly';

    protected $date = 1;

    public function __construct(string $string)
    {
        $this->usableMatch = strpos($string, 'monthly') !== false;

        if (preg_match('/monthly on the (\d+)/', $string, $matches)) {
            $this->date = (int) $matches[1];

            if ($this->date < 1 || $this->date > 31) {
                $this->date = 1;
            }
        }
    }

    public function getNextDate(SecondlessTimeString $setTime, $timezone = null): DateString
    {
        $setTimeIsLaterThanNow = $setTime->laterThanNow($timezone);

        $thisMonth = now($timezone)->lastOfMonth();

        // When the request is for date 28, 29, 30 or 31, and the current month
        // has less days than that, set the date to the last day of the month.
        if ($thisMonth->day >= $this->date) {
            $thisMonth->day($this->date);
        }

        $monthlyOnDate = new DateString($thisMonth);

        if ($monthlyOnDate->isAfterToday($timezone)) {
            return $monthlyOnDate;
        }

        if ($monthlyOnDate->isToday($timezone) && $setTimeIsLaterThanNow) {
            return $monthlyOnDate;
        }

        $nextMonth = now($timezone)->addMonth()->lastOfMonth();

        if ($nextMonth->day >= $this->date) {
            $nextMonth->day($this->date);
        }

        return new DateString($nextMonth);
    }
}
