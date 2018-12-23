<?php

namespace App\Meel\When\Formats\Relative;

use Carbon\Carbon;

class RelativeYears extends RelativeWhenFormat
{
    public $years = 0;

    public function __construct($now, string $writtenInput)
    {
        $this->years = $this->parseYears($writtenInput);
    }

    protected function parseYears($writtenInput)
    {
        if (strpos($writtenInput, 'next year') !== false) {
            return 1;
        }

        if (preg_match('/(\d+) years? .*from now/', $writtenInput, $matches)) {
            return (int) $matches[1];
        }

        if (preg_match('/in (\d+) years?/', $writtenInput, $matches)) {
            return (int) $matches[1];
        }

        if (preg_match('/in .*and (\d+) years?/', $writtenInput, $matches)) {
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
