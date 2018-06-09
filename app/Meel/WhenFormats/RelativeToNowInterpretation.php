<?php

namespace App\Meel\WhenFormats;

use App\Support\DateTime\SecondlessTimeString;
use App\Support\Enums\Days;
use Illuminate\Support\Carbon;
use LogicException;

class RelativeToNowInterpretation
{
    protected $string;

    protected $timezone;

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

    public function __construct(string $string, $timezone = null)
    {
        $this->string = $string;

        $this->timezone = $timezone;

        $this->isRelativeToNow = $this->interpretNow($string);

        if ($this->isRelativeToNow()) {
            $this->years = $this->interpretYears($string);

            $this->months = $this->interpretMonths($string);

            $this->weeks = $this->interpretWeeks($string);

            $this->days = $this->interpretDays($string);

            $this->hours = $this->interpretHours($string);

            $this->minutes = $this->interpretMinutes($string);
        }
    }

    public function isRelativeToNow()
    {
        return $this->isRelativeToNow;
    }

    public function hasSpecifiedTime()
    {
        return $this->isRelativeToNow() && ($this->timeOffsetFromNow || $this->hours || $this->minutes);
    }

    public function getTime(): Carbon
    {
        if (! $this->isRelativeToNow()) {
            throw new LogicException('The given input is not relative to now');
        }

        $dateTime = now($this->timezone)
            ->addYears($this->years)
            ->addMonths($this->months)
            ->addWeeks($this->weeks)
            ->addDays($this->days);

        if ($this->hasSpecifiedTime()) {
            $dateTime
                ->second(0) // Ensure the seconds are always at :00
                ->addHours($this->hours)
                ->addMinutes($this->minutes);
        } else {
            $dateTime->setTimeFromTimeString($this->getDefaultTimeString());
        }

        return $dateTime;
    }

    protected function getDefaultTimeString()
    {
        return '08:00:00';
    }

    public function getTimeString(): SecondlessTimeString
    {
        return new SecondlessTimeString(
            $this->getTime()->format('H:i:s')
        );
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
            if (preg_match('/in \d+ (minutes|hours)/', $string)) {
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
        //   "next tuesday"
        if (strpos($string, 'next ') === 0) {
            if (preg_match('/(\d+ years?|next year|next month|next week|monday|tuesday|wednesday|thursday|friday|saturday|sunday|mon\b|tues?\b|wed\b|thu\b|fri\b|sat\b|sun\b)/', $string)) {
                return true;
            }
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

        if (preg_match('/next '.Days::regex().'/', $string, $matches)) {
            return days_until_next($matches[1], $this->timezone);
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
}
