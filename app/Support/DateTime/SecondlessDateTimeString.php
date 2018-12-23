<?php

namespace App\Support\DateTime;

use Carbon\Carbon;

class SecondlessDateTimeString
{
    protected $dateString;

    protected $timeString;

    public function __construct($dateString, $timeString)
    {
        $this->dateString = new DateString($dateString);

        $this->timeString = new SecondlessTimeString($timeString);
    }

    public function isBefore($dateTime)
    {
        $carbon = Carbon::parse($dateTime);

        $date = $carbon->format('Y-m-d');
        $time = $carbon->format('H:i:s');

        if ($this->dateString->isBefore($date)) {
            return true;
        }

        if ($this->dateString->isAfter($date)) {
            return false;
        }

        return $this->timeString->earlierThan($time);
    }

    public function changeTimezone($fromTimezone, $toTimezone)
    {
        $changedDateTime = static::fromCarbon(
            Carbon::parse((string) $this, $fromTimezone)->setTimezone($toTimezone)
        );

        $this->dateString = $changedDateTime->getDateString();

        $this->timeString = $changedDateTime->getTimeString();

        return $this;
    }

    public function addMinutes($minutes)
    {
        $changedDateTime = static::fromCarbon(
            Carbon::parse((string) $this)->addMinutes($minutes)
        );

        $this->dateString = $changedDateTime->getDateString();

        $this->timeString = $changedDateTime->getTimeString();

        return $this;
    }

    public function getTimeString(): SecondlessTimeString
    {
        return $this->timeString;
    }

    public function getDateString(): DateString
    {
        return $this->dateString;
    }

    public function __toString()
    {
        return $this->dateString.' '.$this->timeString;
    }

    public static function now($timezone)
    {
        return new static(DateString::now($timezone), SecondlessTimeString::now($timezone));
    }

    public static function fromCarbon($carbon)
    {
        return new static($carbon, $carbon);
    }
}
