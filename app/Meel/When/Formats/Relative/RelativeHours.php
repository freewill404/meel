<?php

namespace App\Meel\When\Formats\Relative;

use Carbon\Carbon;

class RelativeHours extends RelativeWhenFormat
{
    protected $specifiesTime = true;

    public $hours = 0;

    public function __construct($now, string $writtenInput)
    {
        $this->hours = $this->parseHours($writtenInput);
    }

    protected function parseHours($writtenInput)
    {
        if (preg_match('/in (\d+) hours?/', $writtenInput, $matches)) {
            return (int) $matches[1];
        }

        if (preg_match('/in .*and (\d+) hours?/', $writtenInput, $matches)) {
            return (int) $matches[1];
        }

        if (preg_match('/(\d+) hours? .*from now/', $writtenInput, $matches)) {
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
