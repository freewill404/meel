<?php

namespace App\Meel\When\Formats\Relative;

use Carbon\Carbon;

class RelativeMonths extends RelativeWhenFormat
{
    public $months = 0;

    public function __construct($now, string $writtenInput)
    {
        $this->months = $this->parseMonths($writtenInput);
    }

    protected function parseMonths($writtenInput)
    {
        if (strpos($writtenInput, 'next month') !== false) {
            return 1;
        }

        if (preg_match('/(\d+) months? .*from now/', $writtenInput, $matches)) {
            return (int) $matches[1];
        }

        if (preg_match('/in (\d+) months?/', $writtenInput, $matches)) {
            return (int) $matches[1];
        }

        if (preg_match('/in .*and (\d+) months?/', $writtenInput, $matches)) {
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
