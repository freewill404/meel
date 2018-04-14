<?php

namespace App\Meel;

use Illuminate\Support\Carbon;
use LogicException;

class TimeInterpretation
{
    protected $string;

    protected $time;

    public function __construct(string $string)
    {
        $this->string = $string;

        $this->time = $this->interpretAbsoluteTime($string);
    }

    public function isValidTime()
    {
        return (bool) $this->time;
    }

    public function getTime(): Carbon
    {
        if (! $this->isValidTime()) {
            throw new LogicException('The given input does not contain a time');
        }

        [$hours, $minutes] = explode(':', $this->time);

        return Carbon::createFromFormat('U', 0)->setTime($hours, $minutes, 0, 0);
    }

    public function getTimeString(): string
    {
        return $this->getTime()->format('H:i:s');
    }

    protected function interpretAbsoluteTime($string)
    {
        // Match patterns like:
        //   "1:30"
        //   "12:00"
        if (preg_match('/(\d?\d:\d\d)/', $string, $matches)) {
            return $matches[1];
        }

        // Match patterns like:
        //   "at 2"
        if (preg_match('/at (\d?\d)/', $string, $matches)) {
            return $matches[1].':00';
        }

        return false;
    }
}
