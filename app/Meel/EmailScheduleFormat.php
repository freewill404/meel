<?php

namespace App\Meel;

use App\Meel\DateTime\DateString;
use App\Meel\DateTime\TimeString;
use App\Meel\WhenFormats\DateInterpretation;
use App\Meel\WhenFormats\RecurringInterpretation;
use App\Meel\WhenFormats\TimeInterpretation;
use App\Support\Enums\Days;
use App\Support\Enums\Intervals;
use App\Support\Enums\Months;

class EmailScheduleFormat
{
    protected $writtenInput;

    protected $preparedWrittenInput;

    protected $dateInterpretation;

    protected $relativeNow;

    protected $timeInterpretation;

    protected $recurringInterpretation;

    protected $timezone;

    public function __construct($writtenInput, $timezone = null)
    {
        $this->writtenInput = $writtenInput;

        $this->timezone = $timezone;

        $this->preparedWrittenInput = WhenString::prepare($writtenInput);

        $this->dateInterpretation = new DateInterpretation($this->preparedWrittenInput, $timezone);

        $this->relativeNow = new RelativeNow($this->preparedWrittenInput, $timezone);

        $this->timeInterpretation = new TimeInterpretation($this->preparedWrittenInput);

        $this->recurringInterpretation = new RecurringInterpretation($this->preparedWrittenInput);
    }

    public function isUsableInterpretation(): bool
    {
        return $this->nextOccurrence() !== false;
    }

    public function isRecurring(): bool
    {
        return (bool) $this->getInterval();
    }

    public function getInterval()
    {
        return $this->recurringInterpretation->isUsableMatch() ? 'TODO' : false;
    }

    public function nextOccurrence()
    {
        $timeString = $this->getNextInterpretedTime() ?: $this->getDefaultTime();

        $dateString = $this->getNextInterpretedDate($timeString);

        return $dateString
            ? $dateString.' '.$timeString
            : false;
    }

    protected function getNextInterpretedDate(TimeString $setTime): ?DateString
    {
        if ($this->isRecurring()) {
            return $this->recurringInterpretation->getNextDate($setTime, $this->timezone);
        }

        if ($this->dateInterpretation->hasSpecifiedDate()) {
            return $this->dateInterpretation->getDateString();
        }

        if ($this->relativeNow->isRelativeToNow()) {
            return new DateString($this->relativeNow->getTime()->format('Y-m-d'));
        }

        return null;
    }

    protected function getNextInterpretedTime(): ?TimeString
    {
        if ($this->timeInterpretation->isUsableMatch()) {
            return $this->timeInterpretation->getTimeString();
        }

        if ($this->relativeNow->hasSpecifiedTime()) {
            return $this->relativeNow->getTimeString();
        }

        return null;
    }

    protected function getDefaultTime(): TimeString
    {
        return new TimeString('08:00:00');
    }
}
