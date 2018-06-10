<?php

namespace App\Meel\WhenFormats\Recurring;

use App\Support\DateTime\DateString;
use App\Support\DateTime\SecondlessTimeString;

class Daily extends RecurringWhenFormat
{
    protected $intervalDescription = 'daily';

    public function __construct(string $string)
    {
        $this->usableMatch = strpos($string, 'daily') !== false;
    }

    public function getNextDate(SecondlessTimeString $setTime, $timezone = null): DateString
    {
        return $setTime->laterThanNow($timezone)
            ? DateString::now($timezone)
            : DateString::tomorrow($timezone);
    }
}
