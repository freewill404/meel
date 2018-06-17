<?php

namespace App\Meel\WhenFormats;

use App\Support\DateTime\SecondlessTimeString;
use RuntimeException;

class TimeInterpretation
{
    protected $time;

    public function __construct(string $string)
    {
        $this->time = $this->interpretTime($string);

        if ($this->time) {
            [$hours, $minutes] = explode(':', $this->time);

            if ($hours > 23 || $minutes > 59) {
                $this->time = null;
            }
        }
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

    public function getTimeString(): SecondlessTimeString
    {
        if (! $this->isUsableMatch()) {
            throw new RuntimeException('The given input does not contain a time');
        }

        return new SecondlessTimeString($this->time);
    }
}
