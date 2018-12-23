<?php

namespace App\Meel\When\Formats\Relative;

use Carbon\Carbon;

class RelativeMinutes extends RelativeWhenFormat
{
    protected $specifiesTime = true;

    public $minutes = 0;

    public function __construct($now, string $writtenInput)
    {
        $this->minutes = $this->parseMinutes($writtenInput);
    }

    protected function parseMinutes($writtenInput)
    {
        if (preg_match('/in (\d+) minutes?/', $writtenInput, $matches)) {
            return (int) $matches[1];
        }

        if (preg_match('/in .*and (\d+) minutes?/', $writtenInput, $matches)) {
            return (int) $matches[1];
        }

        if (preg_match('/(\d+) minutes? .*from now/', $writtenInput, $matches)) {
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
