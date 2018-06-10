<?php

namespace App\Meel\WhenFormats\Relative;

use Carbon\Carbon;

class RelativeMinutes extends RelativeWhenFormat
{
    protected $specifiesTime = true;

    public $minutes = 0;

    public function __construct(string $string, $timezone = null)
    {
        $this->minutes = $this->parseMinutes($string);
    }

    protected function parseMinutes($string)
    {
        if (preg_match('/in (\d+) minutes?/', $string, $matches)) {
            return (int) $matches[1];
        }

        if (preg_match('/in .*and (\d+) minutes?/', $string, $matches)) {
            return (int) $matches[1];
        }

        if (preg_match('/(\d+) minutes? .*from now/', $string, $matches)) {
            return (int) $matches[1];
        }

        return 0;
    }

    public function isUsableMatch(): bool
    {
        return (bool) $this->minutes;
    }

    public function transformNow(Carbon $carbon)
    {
        $carbon->addMinutes($this->minutes);
    }
}
