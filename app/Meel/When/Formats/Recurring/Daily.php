<?php

namespace App\Meel\When\Formats\Recurring;

use App\Support\DateTime\DateString;
use App\Support\DateTime\SecondlessTimeString;
use Carbon\Carbon;

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

    public function getNextDate(Carbon $now, SecondlessTimeString $setTime): DateString
    {
        return $this->daysInterval === 1 && $setTime->laterThan($now)
            ? new DateString($now)
            : new DateString($now->copy()->addDays($this->daysInterval));
    }
}
