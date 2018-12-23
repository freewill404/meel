<?php

namespace App\Meel\When\Formats\Relative;

use Carbon\Carbon;

class RelativeWeeks extends RelativeWhenFormat
{
    public $weeks = 0;

    public function __construct($now, string $writtenInput)
    {
        $this->weeks = $this->parseWeeks($writtenInput);
    }

    protected function parseWeeks($writtenInput)
    {
        if (strpos($writtenInput, 'next week') !== false) {
            return 1;
        }

        if (preg_match('/(\d+) weeks? .*from now/', $writtenInput, $matches)) {
            return (int) $matches[1];
        }

        if (preg_match('/in (\d+) weeks?/', $writtenInput, $matches)) {
            return (int) $matches[1];
        }

        if (preg_match('/in .*and (\d+) weeks?/', $writtenInput, $matches)) {
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
