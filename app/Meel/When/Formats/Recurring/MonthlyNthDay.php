<?php

namespace App\Meel\When\Formats\Recurring;

use App\Support\DateTime\DateString;
use App\Support\DateTime\SecondlessTimeString;
use Carbon\Carbon;

class MonthlyNthDay extends RecurringWhenFormat
{
    protected $intervalDescription = 'monthly';

    private $nth;

    private $nthNumber;

    private $day;

    public function __construct(string $string)
    {
        // Match:
        //   "every third saturday of the month"
        //   "the last saturday of the month"
        //   "the last day of the month"
        //   "the 12th day of the month"
        $this->usableMatch = preg_match('/(?:^| )(\d\d?(?:st|nd|rd|th)|last) (day|monday|tuesday|wednesday|thursday|friday|saturday|sunday) of the month/', $string, $matches);

        if (! $this->usableMatch) {
            return;
        }

        // Carbon's "modify" needs written ordinal numbers
        $this->nth = strtr($matches[1], [
            '1st' => 'first',
            '2nd' => 'second',
            '3rd' => 'third',
            '4th' => 'fourth',
        ]);

        $this->nthNumber = $matches[1] === 'last'
            ? null
            : substr($matches[1], 0, strlen($matches[1]) - 2);

        $this->day = $matches[2];

        if ($this->day !== 'day' && $this->nthNumber > 4) {
            $this->usableMatch = false;
        }
    }

    public function getNextDate(Carbon $now, SecondlessTimeString $setTime): DateString
    {
        $setTimeIsLaterThanNow = $setTime->laterThan($now);

        if ($this->day === 'day' && $this->nth !== 'last') {
            return $this->nextNthDay($now, $setTimeIsLaterThanNow);
        }

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

    private function nextNthDay(Carbon $now, $setTimeIsLaterThanNow)
    {
        $baseMonth = $now->copy()->lastOfMonth();

        // When the request is for date 28, 29, 30 or 31, and the current month
        // has less days than that, set the date to the last day of the month.
        if ($baseMonth->day >= $this->nthNumber) {
            $baseMonth->day($this->nthNumber);
        }

        $nthDayThisMonth = new DateString($baseMonth);

        if ($nthDayThisMonth->isAfter($now)) {
            return $nthDayThisMonth;
        }

        if ($nthDayThisMonth->isSame($now) && $setTimeIsLaterThanNow) {
            return $nthDayThisMonth;
        }

        $nextMonth = $now->copy()->addMonthNoOverflow(1)->lastOfMonth();

        if ($nextMonth->day >= $this->nthNumber) {
            $nextMonth->day($this->nthNumber);
        }

        return new DateString($nextMonth);
    }
}
