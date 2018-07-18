<?php

namespace App\Meel\Schedules;

use App\Support\DateTime\DateString;
use App\Support\DateTime\SecondlessDateTimeString;
use App\Support\DateTime\SecondlessTimeString;
use App\Meel\Schedules\WhenFormats\DateInterpretation;
use App\Meel\Schedules\WhenFormats\RecurringInterpretation;
use App\Meel\Schedules\WhenFormats\RelativeToNowInterpretation;
use App\Meel\Schedules\WhenFormats\TimeInterpretation;

class EmailScheduleFormat
{
    protected $dateInterpretation;

    protected $relativeNow;

    protected $timeInterpretation;

    protected $recurringInterpretation;

    protected $timezone;

    public function __construct($writtenInput, $timezone = null)
    {
        $this->timezone = $timezone;

        $preparedWrittenInput = WhenString::prepare($writtenInput);

        $this->dateInterpretation = new DateInterpretation($preparedWrittenInput, $timezone);

        $this->relativeNow = new RelativeToNowInterpretation($preparedWrittenInput, $timezone);

        $this->timeInterpretation = new TimeInterpretation($preparedWrittenInput);

        $this->recurringInterpretation = new RecurringInterpretation($preparedWrittenInput);
    }

    public function isUsableInterpretation(): bool
    {
        return (bool) $this->nextOccurrence();
    }

    public function isRecurring(): bool
    {
        return $this->recurringInterpretation->isUsableMatch();
    }

    public function getIntervalDescription()
    {
        return $this->recurringInterpretation->intervalDescription();
    }

    public function nextOccurrence(): ?SecondlessDateTimeString
    {
        $time = $this->getNextInterpretedTime();

        $usedDefaultTime = false;

        if (! $time) {
            $time = $this->getDefaultTime();

            $usedDefaultTime = true;
        }

        $date = $this->getNextInterpretedDate($time);

        if (! $date && $usedDefaultTime) {
            return null;
        }

        $dateTime = new SecondlessDateTimeString($date ?? now($this->timezone), $time);

        return $dateTime->isInThePast($this->timezone) ? null : $dateTime;
    }

    protected function getNextInterpretedDate(SecondlessTimeString $setTime): ?DateString
    {
        if ($this->isRecurring()) {
            return $this->recurringInterpretation->getNextDate($setTime, $this->timezone);
        }

        if ($this->dateInterpretation->hasSpecifiedDate()) {
            return $this->dateInterpretation->getDateString();
        }

        if ($this->relativeNow->isRelativeToNow()) {
            return $this->relativeNow->getDateString();
        }

        return null;
    }

    protected function getNextInterpretedTime(): ?SecondlessTimeString
    {
        if ($this->timeInterpretation->isUsableMatch()) {
            return $this->timeInterpretation->getTimeString();
        }

        if ($this->relativeNow->hasSpecifiedTime()) {
            return $this->relativeNow->getTimeString();
        }

        return null;
    }

    protected function getDefaultTime(): SecondlessTimeString
    {
        return new SecondlessTimeString('08:00:00');
    }
}
