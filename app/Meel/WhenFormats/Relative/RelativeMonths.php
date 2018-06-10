<?php

namespace App\Meel\WhenFormats\Relative;

use Carbon\Carbon;

class RelativeMonths extends RelativeWhenFormat
{
    public $months = 0;

    public function __construct(string $string, $timezone = null)
    {
        $this->months = $this->parseMonths($string);
    }

    protected function parseMonths($string)
    {
        if (strpos($string, 'next month') !== false) {
            return 1;
        }

        if (preg_match('/(\d+) months? .*from now/', $string, $matches)) {
            return (int) $matches[1];
        }

        if (preg_match('/in (\d+) months?/', $string, $matches)) {
            return (int) $matches[1];
        }

        if (preg_match('/in .*and (\d+) months?/', $string, $matches)) {
            return (int) $matches[1];
        }

        return 0;
    }

    public function isUsableMatch(): bool
    {
        return (bool) $this->months;
    }

    public function transformNow(Carbon $carbon)
    {
        $carbon->addMonths($this->months);
    }
}
