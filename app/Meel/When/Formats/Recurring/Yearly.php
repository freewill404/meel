<?php

namespace App\Meel\When\Formats\Recurring;

use App\Support\DateTime\DateString;
use App\Support\DateTime\SecondlessTimeString;
use Carbon\Carbon;

class Yearly extends RecurringWhenFormat
{
    protected $intervalDescription = 'yearly';

    protected $yearInterval = 1;

    protected $dateOfTheMonth = 1;

    protected $monthOfTheYear = 'january';

    public function __construct(string $string)
    {
        if (preg_match('/every (\d+) years?/', $string, $matches)) {
            $this->yearInterval = (int) $matches[1];

            $this->usableMatch = $this->yearInterval > 0;

            if ($this->yearInterval > 1) {
                $this->intervalDescription = 'every '.$this->yearInterval.' years';
            }
        }

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

    public function getNextDate(Carbon $now, SecondlessTimeString $setTime): DateString
    {
        $carbon = $now->copy()->modify('this year '.$this->monthOfTheYear);

        if ($this->yearInterval > 1) {
            $carbon->addYearsNoOverflow($this->yearInterval);
        }

        $carbon->lastOfMonth();

        if ($carbon->day >= $this->dateOfTheMonth) {
            $carbon->day($this->dateOfTheMonth);
        }

        $thisYearDateString = new DateString($carbon);

        if ($thisYearDateString->isAfter($now)) {
            return $thisYearDateString;
        }

        if ($thisYearDateString->isSame($now) && $setTime->laterThan($now)) {
            return $thisYearDateString;
        }

        $nextYear = $carbon->addYearsNoOverflow(1)->lastOfMonth();

        if ($nextYear->day >= $this->dateOfTheMonth) {
            $nextYear->day($this->dateOfTheMonth);
        }

        return new DateString($nextYear);
    }
}
