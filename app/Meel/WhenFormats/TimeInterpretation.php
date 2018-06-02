<?php

namespace App\Meel\WhenFormats;

use App\Meel\DateTime\TimeString;
use RuntimeException;

class TimeInterpretation
{
    protected $time;

    public function __construct(string $string)
    {
        $this->time = $this->interpretTime($string);
    }

    protected function interpretTime($string)
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

    public function isUsableMatch()
    {
        return (bool) $this->time;
    }

    public function getTimeString(): TimeString
    {
        if (! $this->isUsableMatch()) {
            throw new RuntimeException('The given input does not contain a time');
        }

        [$hours, $minutes] = explode(':', $this->time);

        return new TimeString(
            now()->setTime($hours, $minutes, 0, 0)
        );
    }
}