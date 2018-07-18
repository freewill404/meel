<?php

namespace App\Meel\Schedules\WhenFormats\Relative;

use Carbon\Carbon;

class RelativeWeeks extends RelativeWhenFormat
{
    public $weeks = 0;

    public function __construct(string $string, $timezone = null)
    {
        $this->weeks = $this->parseWeeks($string);
    }

    protected function parseWeeks($string)
    {
        if (strpos($string, 'next week') !== false) {
            return 1;
        }

        if (preg_match('/(\d+) weeks? .*from now/', $string, $matches)) {
            return (int) $matches[1];
        }

        if (preg_match('/in (\d+) weeks?/', $string, $matches)) {
            return (int) $matches[1];
        }

        if (preg_match('/in .*and (\d+) weeks?/', $string, $matches)) {
            return (int) $matches[1];
        }

        return 0;
    }

    public function isUsableMatch(): bool
    {
        return (bool) $this->weeks;
    }

    public function transformNow(Carbon $carbon)
    {
        $carbon->addWeeks($this->weeks);
    }
}
