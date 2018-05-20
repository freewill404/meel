<?php

namespace App\Meel\WhenFormats\Recurring;

use App\Meel\DateTime\DateString;
use App\Meel\DateTime\TimeString;
use Carbon\Carbon;

class MonthlyNthDay extends RecurringWhenFormat
{
    protected $usableMatch = false;

    protected $nth;

    protected $day;

    public function __construct(string $string)
    {
        // Match:
        //   "every third saturday of the month"
        //   "the last saturday of the month"
        $this->usableMatch = preg_match('/(^| )(1st|2nd|3rd|4th|last) (monday|tuesday|wednesday|thursday|friday|saturday|sunday) of the month/', $string, $matches);

        if ($this->usableMatch) {
            // Carbon::parse needs written ordinal numbers
            $this->nth = strtr($matches[2], [
                '1st' => 'first',
                '2nd' => 'second',
                '3rd' => 'third',
                '4th' => 'fourth',
            ]);

            $this->day = $matches[3];
        }
    }

    public function isUsableMatch(): bool
    {
        return (bool) $this->usableMatch;
    }

    public function getNextDate(TimeString $setTime, $timezone = null): DateString
    {
        $setTimeIsLaterThanNow = $setTime->laterThanNow($timezone);

        $nthDayThisMonth = new DateString(
            Carbon::parse($this->nth.' '.$this->day.' of this month', $timezone)
        );

        if ($nthDayThisMonth->isAfterToday($timezone)) {
            return $nthDayThisMonth;
        }

        if ($nthDayThisMonth->isToday($timezone) && $setTimeIsLaterThanNow) {
            return $nthDayThisMonth;
        }

        return new DateString(
            Carbon::parse($this->nth.' '.$this->day.' of next month', $timezone)
        );
    }

    public function intervalDescription()
    {
        return 'monthly';
    }
}
