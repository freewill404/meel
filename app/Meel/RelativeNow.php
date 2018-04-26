<?php

namespace App\Meel;

use Illuminate\Support\Carbon;
use LogicException;

class RelativeNow
{
    protected $string;

    protected $isRelativeToNow = false;

    /**
     * Determines if the time should be offset from
     * now, or should use the default time.
     */
    protected $timeOffsetFromNow = false;

    protected $years = 0;

    protected $months = 0;

    protected $weeks = 0;

    protected $days = 0;

    protected $hours = 0;

    protected $minutes = 0;

    protected $seconds = 0;

    public function __construct(string $string)
    {
        $this->string = $string;

        $this->isRelativeToNow = $this->interpretNow($string);

        if ($this->isRelativeToNow()) {
            $this->years = $this->interpretYears($string);

            $this->months = $this->interpretMonths($string);

            $this->weeks = $this->interpretWeeks($string);

            $this->days = $this->interpretDays($string);

            $this->hours = $this->interpretHours($string);

            $this->minutes = $this->interpretMinutes($string);

            $this->seconds = $this->interpretSeconds($string);
        }
    }

    public function isRelativeToNow()
    {
        return $this->isRelativeToNow;
    }

    public function hasSpecifiedTime()
    {
        // ($this->years || $this->months || $this->weeks || $this->days)

        return $this->isRelativeToNow() && ($this->timeOffsetFromNow || $this->hours || $this->minutes || $this->seconds);
    }

    public function getTime(): Carbon
    {
        if (! $this->isRelativeToNow()) {
            throw new LogicException('The given input is not relative to now');
        }

        $dateTime = now()
            ->addYears($this->years)
            ->addMonths($this->months)
            ->addWeeks($this->weeks)
            ->addDays($this->days);

        if ($this->hasSpecifiedTime()) {
            $dateTime->addHours($this->hours)
                ->addMinutes($this->minutes)
                ->addSeconds($this->seconds);
        } else {
            $dateTime->setTimeFromTimeString($this->getDefaultTimeString());
        }

        return $dateTime;
    }

    protected function getDefaultTimeString()
    {
        return '08:00:00';
    }

    public function getTimeString()
    {
        return $this->getTime()->format('H:i:s');
    }

    protected function interpretNow($string)
    {
        if ($string === 'now') {
            $this->timeOffsetFromNow = true;

            return true;
        }

        if ($string === 'tomorrow') {
            return true;
        }

        // Match strings like:
        //   "in 1 hour"
        if (strpos($string, 'in ') === 0) {
            if (preg_match('/in \d+ (seconds|minutes|hours)/', $string)) {
                $this->timeOffsetFromNow = true;
            }

            return true;
        }

        // Match strings like:
        //   "at 5"
        //   "tomorrow at 5"
        if (preg_match('/(^| )at \d/', $string)) {
            return true;
        }

        // Match strings like:
        //   "next year"
        if (strpos($string, 'next ') === 0) {
            return true;
        }

        // Match patterns like:
        //   "right now"
        //   "an hour from now"
        if (preg_match('/ now$/', $string)) {
            if (! preg_match('/(days?|weeks?|months?|years?) from now$/', $string)) {
                $this->timeOffsetFromNow = true;
            }

            return true;
        }

        return false;
    }

    protected function interpretYears($string)
    {
        if (strpos($string, 'next year') !== false) {
            return 1;
        }

        if (preg_match('/(\d+) years?/', $string, $matches)) {
            return (int) $matches[1];
        }

        return 0;
    }

    protected function interpretMonths($string)
    {
        if (strpos($string, 'next month') !== false) {
            return 1;
        }

        if (preg_match('/(\d+) months?/', $string, $matches)) {
            return (int) $matches[1];
        }

        return 0;
    }

    protected function interpretWeeks($string)
    {
        if (strpos($string, 'next week') !== false) {
            return 1;
        }

        if (preg_match('/(\d+) weeks?/', $string, $matches)) {
            return (int) $matches[1];
        }

        return 0;
    }

    protected function interpretDays($string)
    {
        if (strpos($string, 'tomorrow') !== false) {
            return 1;
        }

        if (preg_match('/(\d+) days?/', $string, $matches)) {
            return (int) $matches[1];
        }

        return 0;
    }

    protected function interpretHours($string)
    {
        if (preg_match('/(\d+) hours?/', $string, $matches)) {
            return (int) $matches[1];
        }

        return 0;
    }

    protected function interpretMinutes($string)
    {
        if (preg_match('/(\d+) minutes?/', $string, $matches)) {
            return (int) $matches[1];
        }

        return 0;
    }

    protected function interpretSeconds($string)
    {
        if (preg_match('/(\d+) seconds?/', $string, $matches)) {
            return (int) $matches[1];
        }

        return 0;
    }
}
