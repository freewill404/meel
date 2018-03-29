<?php

namespace App\Meel;

use Illuminate\Support\Carbon;
use LogicException;

class RelativeNow
{
    protected $string;

    protected $isRelativeToNow = false;

    protected $years  = 0;
    protected $months = 0;
    protected $weeks  = 0;
    protected $days   = 0;

    protected $hours   = 0;
    protected $minutes = 0;
    protected $seconds = 0;

    public function __construct(string $string)
    {
        $this->string = $string;

        $this->isRelativeToNow = $this->interpretNow($string);

        if ($this->isRelativeToNow()) {
            $this->years = $this->interpretYears($string);

            
        }
    }

    public function isRelativeToNow()
    {
        return $this->isRelativeToNow;
    }

    public function getTime(): Carbon
    {
        if (! $this->isRelativeToNow()) {
            throw new LogicException('The given input is not relative to now');
        }

        return now()
            ->addYears($this->years)
            ->addMonths($this->months)
            ->addWeeks($this->weeks)
            ->addDays($this->days)
            ->addHours($this->hours)
            ->addMinutes($this->minutes)
            ->addSeconds($this->seconds);
    }

    protected function interpretNow($string)
    {
        if ($string === 'now') {
            return true;
        }

        // Match strings like:
        //   "in 1 hour"
        if (strpos($string, 'in ') === 0) {
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

    }

    protected function interpretWeeks($string)
    {

    }

    protected function interpretDays($string)
    {

    }

    protected function interpretHours($string)
    {

    }

    protected function interpretMinutes($string)
    {

    }

    protected function interpretSeconds($string)
    {

    }
}
