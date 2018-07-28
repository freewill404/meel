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
