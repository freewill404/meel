<?php

namespace App\Meel\WhenFormats\Relative;

use Carbon\Carbon;

class RelativeHours extends RelativeWhenFormat
{
    protected $specifiesTime = true;

    public $hours = 0;

    public function __construct(string $string, $timezone = null)
    {
        $this->hours = $this->parseHours($string);
    }

    protected function parseHours($string)
    {
        if (preg_match('/in (\d+) hours?/', $string, $matches)) {
            return (int) $matches[1];
        }

        if (preg_match('/in .*and (\d+) hours?/', $string, $matches)) {
            return (int) $matches[1];
        }

        if (preg_match('/(\d+) hours? .*from now/', $string, $matches)) {
            return (int) $matches[1];
        }

        return 0;
    }

    public function isUsableMatch(): bool
    {
        return (bool) $this->hours;
    }

    public function transformNow(Carbon $carbon)
    {
        $carbon->addHours($this->hours);
    }
}
