<?php

namespace App\Meel\When\Formats\Recurring;

use App\Support\DateTime\DateString;
use App\Support\DateTime\SecondlessTimeString;
use Carbon\Carbon;

class Weekly extends RecurringWhenFormat
{
    protected $intervalDescription = 'weekly';

    protected $weekInterval = 1;

    protected $day;

    public function __construct(string $string)
    {
        if (preg_match('/every (\d+) weeks?/', $string, $matches)) {
            $this->weekInterval = (int) $matches[1];

            $this->usableMatch = $this->weekInterval > 0;

            if ($this->weekInterval > 1) {
                $this->intervalDescription = 'every '.$this->weekInterval.' weeks';
            }
        }

        if (preg_match('/(monday|tuesday|wednesday|thursday|friday|saturday|sunday)/', $string, $matches)) {
            $this->day = $matches[1];
        }
    }

    public function getNextDate(Carbon $now, SecondlessTimeString $setTime): DateString
    {
        $setTimeIsLaterThanNow = $setTime->laterThan($now);

        $weeklyOnDay = new DateString(
            $carbon = $now->copy()->modify('this week '.$this->day)
        );

        // Weekly schedules  with an interval essentially work like this:
        //     "in 2 weeks on wednesday === in 2 wednesdays from now".
        //
        // If the schedule is for a wednesday, and today is tuesday,
        // the next wednesday (tomorrow) counts as the first week.
        if ($this->weekInterval > 1) {
            $carbon->addWeeks(
                $this->weekInterval - ($weeklyOnDay->isAfter($now) ? 1 : 0)
            );
        }

        $weeklyOnDay = new DateString($carbon);

        if ($weeklyOnDay->isAfter($now)) {
            return $weeklyOnDay;
        }

        if ($weeklyOnDay->isSame($now) && $setTimeIsLaterThanNow) {
            return $weeklyOnDay;
        }

        return new DateString(
            $now->copy()->modify('next week '.$this->day)
        );
    }
}
