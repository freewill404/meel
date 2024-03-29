<?php

namespace App\Meel\When\Formats\Recurring;

use App\Support\DateTime\DateString;
use App\Support\DateTime\SecondlessTimeString;
use Carbon\Carbon;

class Monthly extends RecurringWhenFormat
{
    protected $intervalDescription = 'monthly';

    protected $monthInterval = 1;

    protected $date = 1;

    public function __construct(string $string)
    {
        if (preg_match('/every (\d+) months?/', $string, $matches)) {
            $this->monthInterval = (int) $matches[1];

            $this->usableMatch = $this->monthInterval > 0;

            if ($this->monthInterval > 1) {
                $this->intervalDescription = 'every '.$this->monthInterval.' months';
            }
        }

        if (preg_match('/every \d+ months? on the (\d+)/', $string, $matches)) {
            $this->date = (int) $matches[1];

            if ($this->date < 1 || $this->date > 31) {
                $this->date = 1;
            }
        }
    }

    public function getNextDate(Carbon $now, SecondlessTimeString $setTime): DateString
    {
        $setTimeIsLaterThanNow = $setTime->laterThan($now);

        $baseMonth = $this->monthInterval === 1
            ? $now->copy()->lastOfMonth()
            : $now->copy()->addMonthNoOverflow($this->monthInterval)->lastOfMonth();

        // When the request is for date 28, 29, 30 or 31, and the current month
        // has less days than that, set the date to the last day of the month.
        if ($baseMonth->day >= $this->date) {
            $baseMonth->day($this->date);
        }

        $monthlyOnDate = new DateString($baseMonth);

        if ($monthlyOnDate->isAfter($now)) {
            return $monthlyOnDate;
        }

        if ($monthlyOnDate->isSame($now) && $setTimeIsLaterThanNow) {
            return $monthlyOnDate;
        }

        $nextMonth = $now->copy()->addMonthNoOverflow($this->monthInterval)->lastOfMonth();

        if ($nextMonth->day >= $this->date) {
            $nextMonth->day($this->date);
        }

        return new DateString($nextMonth);
    }
}
