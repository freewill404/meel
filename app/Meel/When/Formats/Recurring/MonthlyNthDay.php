<?php

namespace App\Meel\When\Formats\Recurring;

use App\Support\DateTime\DateString;
use App\Support\DateTime\SecondlessTimeString;
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
        $this->usableMatch = preg_match('/(?:^| )(1st|2nd|3rd|4th|last) (monday|tuesday|wednesday|thursday|friday|saturday|sunday) of the month/', $string, $matches);

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

    public function getNextDate(Carbon $now, SecondlessTimeString $setTime): DateString
    {
        $setTimeIsLaterThanNow = $setTime->laterThan($now);

        $nthDayThisMonth = new DateString(
            $now->copy()->modify("{$this->nth} {$this->day} of this month")
        );

        if ($nthDayThisMonth->isAfter($now)) {
            return $nthDayThisMonth;
        }

        if ($nthDayThisMonth->isSame($now) && $setTimeIsLaterThanNow) {
            return $nthDayThisMonth;
        }

        return new DateString(
            $now->copy()->modify("{$this->nth} {$this->day} of next month")
        );
    }
}
