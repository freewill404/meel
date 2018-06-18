<?php

namespace App\Meel\WhenFormats\Recurring;

use App\Support\DateTime\DateString;
use App\Support\DateTime\SecondlessTimeString;
use App\Support\Enums\Days;
use Carbon\Carbon;

class MonthlyNthDay extends RecurringWhenFormat
{
    protected $intervalDescription = 'monthly';

    protected $nth;

    protected $day;

    public function __construct(string $string)
    {
        // Match:
        //   "every third saturday of the month"
        //   "the last saturday of the month"
        $this->usableMatch = preg_match('/(?:^| )(1st|2nd|3rd|4th|last) '.Days::regex().' of the month/', $string, $matches);

        if ($this->usableMatch) {
            // Carbon::parse needs written ordinal numbers
            $this->nth = strtr($matches[1], [
                '1st' => 'first',
                '2nd' => 'second',
                '3rd' => 'third',
                '4th' => 'fourth',
            ]);

            $this->day = $matches[2];
        }
    }

    public function getNextDate(SecondlessTimeString $setTime, $timezone = null): DateString
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
}
