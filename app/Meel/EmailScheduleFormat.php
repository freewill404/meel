<?php

namespace App\Meel;

use App\Support\DateTime\DateString;
use App\Support\DateTime\DateTimeString;
use App\Support\DateTime\TimeString;
use App\Meel\WhenFormats\DateInterpretation;
use App\Meel\WhenFormats\RecurringInterpretation;
use App\Meel\WhenFormats\RelativeToNowInterpretation;
use App\Meel\WhenFormats\TimeInterpretation;

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

        $this->relativeNow = new RelativeToNowInterpretation($this->preparedWrittenInput, $timezone);

        $this->timeInterpretation = new TimeInterpretation($this->preparedWrittenInput);

        $this->recurringInterpretation = new RecurringInterpretation($this->preparedWrittenInput);
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

    public function nextOccurrence(): ?DateTimeString
    {
        $timeString = $this->getNextInterpretedTime() ?: $this->getDefaultTime();

        $dateString = $this->getNextInterpretedDate($timeString);

        return $dateString
            ? new DateTimeString($dateString, $timeString)
            : null;
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
