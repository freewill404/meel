<?php

namespace App\Support\DateTime;

class DateTimeString
{
    protected $dateString;

    protected $timeString;

    public function __construct($dateString, $timeString)
    {
        $this->dateString = new DateString($dateString);

        $this->timeString = new TimeString($timeString);
    }

    public function isInThePast($timezone): bool
    {
        if ($this->dateString->isBeforeToday($timezone)) {
            return true;
        }

        if ($this->dateString->isAfterToday($timezone)) {
            return false;
        }

        return $this->timeString->earlierThanNow($timezone);
    }

    public function __toString()
    {
        return $this->dateString.' '.$this->timeString;
    }
}
