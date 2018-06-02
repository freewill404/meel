<?php

namespace App\Meel\DateTime;

class DateTimeString
{
    protected $dateString;

    protected $timeString;

    public function __construct($dateString, $timeString)
    {
        $this->dateString = new DateString($dateString);

        $this->timeString = new TimeString($timeString);
    }

    public function __toString()
    {
        return $this->dateString.' '.$this->timeString;
    }
}
