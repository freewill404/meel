<?php

namespace App\Support\DateTime;

class SecondlessDateTimeString
{
    protected $dateString;

    protected $timeString;

    public function __construct($dateString, $timeString)
    {
        $this->dateString = new DateString($dateString);

        $this->timeString = new SecondlessTimeString($timeString);
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

    public static function now($timezone)
    {
        return new static(DateString::now($timezone), SecondlessTimeString::now($timezone));
    }
}
