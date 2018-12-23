<?php

namespace App\Meel\When;

use App\Support\DateTime\DateString;
use App\Support\DateTime\SecondlessDateTimeString;
use App\Support\DateTime\SecondlessTimeString;
use App\Meel\When\Formats\DateInterpretation;
use App\Meel\When\Formats\RecurringInterpretation;
use App\Meel\When\Formats\RelativeToNowInterpretation;
use App\Meel\When\Formats\TimeInterpretation;
use Carbon\Carbon;

class ScheduleFormat
{
    protected $dateInterpretation;

    protected $relativeNow;

    protected $timeInterpretation;

    protected $recurringInterpretation;

    private $now;

    public function __construct($dateTime, $writtenInput)
    {
        $this->now = $dateTime instanceof Carbon
            ? $dateTime->copy()
            : Carbon::parse($dateTime);

        $preparedWrittenInput = (new WhenString)->prepare($writtenInput);

        $this->dateInterpretation = new DateInterpretation($this->now, $preparedWrittenInput);

        $this->relativeNow = new RelativeToNowInterpretation($this->now, $preparedWrittenInput);

        $this->timeInterpretation = new TimeInterpretation($preparedWrittenInput);

        $this->recurringInterpretation = new RecurringInterpretation($this->now, $preparedWrittenInput);
    }

    public function usable()
    {
        return (bool) $this->nextOccurrence();
    }

    public function recurring()
    {
        return $this->recurringInterpretation->isUsableMatch();
    }

    public function intervalDescription()
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

        $dateTime = new SecondlessDateTimeString($date ?? $this->now, $time);

        return $dateTime->isBefore($this->now) ? null : $dateTime;
    }

    protected function getNextInterpretedDate(SecondlessTimeString $setTime): ?DateString
    {
        if ($this->recurring()) {
            return $this->recurringInterpretation->getNextDate($setTime);
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
