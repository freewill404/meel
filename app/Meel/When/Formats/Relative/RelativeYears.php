<?php

namespace App\Meel\When\Formats\Relative;

use Carbon\Carbon;

class RelativeYears extends RelativeWhenFormat
{
    public $years = 0;

    public function __construct(string $string, $timezone = null)
    {
        $this->years = $this->parseYears($string);
    }

    protected function parseYears($string)
    {
        if (strpos($string, 'next year') !== false) {
            return 1;
        }

        if (preg_match('/(\d+) years? .*from now/', $string, $matches)) {
            return (int) $matches[1];
        }

        if (preg_match('/in (\d+) years?/', $string, $matches)) {
            return (int) $matches[1];
        }

        if (preg_match('/in .*and (\d+) years?/', $string, $matches)) {
            return (int) $matches[1];
        }

        return 0;
    }

    public function isUsableMatch(): bool
    {
        return (bool) $this->years;
    }

    public function transformNow(Carbon $carbon)
    {
        $carbon->addYears($this->years);
    }
}
