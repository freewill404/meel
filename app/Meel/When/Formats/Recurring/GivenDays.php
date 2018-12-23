<?php

namespace App\Meel\When\Formats\Recurring;

use App\Support\DateTime\DateString;
use App\Support\DateTime\SecondlessTimeString;
use Carbon\Carbon;
use RuntimeException;

class GivenDays extends RecurringWhenFormat
{
    protected $intervalDescription = 'on x given days';

    protected $days = [];

    public function __construct(string $string)
    {
        if (preg_match('/every (monday|tuesday|wednesday|thursday|friday|saturday|sunday)/', $string, $matches)) {
            $this->usableMatch = true;

            preg_match_all('/(monday|tuesday|wednesday|thursday|friday|saturday|sunday)/', $string, $matches);

            $this->days = array_unique(array_map('ucfirst', $matches[0]));

            $this->intervalDescription = count($this->days) === 1
                ? 'every '.$this->days[0]
                : 'on '.count($this->days).' given days';
        }
    }

    public function getNextDate(Carbon $now, SecondlessTimeString $setTime): DateString
    {
        if ($this->isGivenDay($now) && $setTime->laterThan($now)) {
            return new DateString($now);
        }

        $carbon = $now->copy();

        for ($i = 0; $i < 7; $i++) {
            $carbon->addDays(1);

            if ($this->isGivenDay($carbon)) {
                return new DateString($carbon);
            }
        }

        throw new RuntimeException('Invalid days');
    }

    private function isGivenDay(Carbon $carbon)
    {
        return in_array($carbon->format('l'), $this->days);
    }
}
