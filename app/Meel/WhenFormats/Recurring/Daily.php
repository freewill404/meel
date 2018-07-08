<?php

namespace App\Meel\WhenFormats\Recurring;

use App\Support\DateTime\DateString;
use App\Support\DateTime\SecondlessTimeString;

class Daily extends RecurringWhenFormat
{
    protected $intervalDescription = 'daily';

    protected $daysInterval = 1;

    public function __construct(string $string)
    {
        if (preg_match('/every (\d+) days?/', $string, $matches)) {
            $this->daysInterval = (int) $matches[1];

            $this->usableMatch = $this->daysInterval > 0;

            if ($this->daysInterval > 1) {
                $this->intervalDescription = 'every '.$this->daysInterval.' days';
            }
        }
    }

    public function getNextDate(SecondlessTimeString $setTime, $timezone = null): DateString
    {
        $now = now($timezone);

        return $this->daysInterval === 1 && $setTime->laterThanNow($timezone)
            ? new DateString($now)
            : new DateString($now->addDays($this->daysInterval));
    }
}
