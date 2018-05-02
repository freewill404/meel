<?php

namespace App\Meel;

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

    public function __construct($writtenInput)
    {
        $this->writtenInput = $writtenInput;

        $this->preparedWrittenInput = TimeString::prepare($writtenInput);

        $this->dateInterpretation = new DateInterpretation($this->preparedWrittenInput);

        $this->relativeNow = new RelativeNow($this->preparedWrittenInput);

        $this->timeInterpretation = new TimeInterpretation($this->preparedWrittenInput);

        $this->recurringInterpretation = new RecurringInterpretation($this->preparedWrittenInput);
    }

    public function isUsableInterpretation(): bool
    {
        return $this->nextOccurrence() !== false;
    }

    public function isRecurring(): bool
    {
        return (bool) $this->recurringInterpretation->getInterval();
    }

    public function nextOccurrence()
    {
        $date = $this->getNextInterpretedDate();

        if (! $date) {
            return false;
        }

        $time = $this->getNextInterpretedTime() ?: $this->getDefaultTime();

        return $date.' '.$time;
    }

    protected function getNextInterpretedDate()
    {
        if ($this->isRecurring()) {
            return $this->getNextRecurringDate();
        }

        if ($this->dateInterpretation->hasSpecifiedDate()) {
            return $this->dateInterpretation->getDateString();
        }

        if ($this->relativeNow->isRelativeToNow()) {
            return $this->relativeNow->getTime()->format('Y-m-d');
        }

        return false;
    }

    protected function getNextInterpretedTime()
    {
        if ($this->timeInterpretation->isValidTime()) {
            return $this->timeInterpretation->getTimeString();
        }

        if ($this->relativeNow->hasSpecifiedTime()) {
            return $this->relativeNow->getTimeString();
        }

        return false;
    }

    protected function getDefaultTime()
    {
        return '08:00:00';
    }

    protected function getNextRecurringDate()
    {
        // Get the specified date, or get the default date
        $dateTime = $this->dateInterpretation->getDate();

        // If it recurs once a year, the month should be set
        $month = $this->recurringInterpretation->getMonthOfTheYear();

        if ($month) {
            $dateTime->month(Months::toInt($month));
        }

        // If it recurs once a month, the day of the month should be set
        $dateOfMonth = $this->recurringInterpretation->getDateOfTheMonth();

        if ($this->recurringInterpretation->getInterval() === Intervals::MONTHLY && $dateOfMonth) {
            $dateTime->day($dateOfMonth);

            while ($dateTime->isPast()) {
                $dateTime->addMonth();
            }
        }

        // If it recurs once a week, the day of the week should be set
        $day = $this->recurringInterpretation->getDayOfTheWeek();

        if ($day) {
            $dateTime->startOfWeek();

            while ($dateTime->isPast() || Days::toInt($day) !== $dateTime->dayOfWeek) {
                $dateTime->addDay();
            }
        }

        if ($this->recurringInterpretation->getInterval() === Intervals::YEARLY) {
            while ($dateTime->isPast()) {
                $dateTime->addYear();
            }
        }

        return $dateTime->format('Y-m-d');
    }
}
