<?php

namespace App\Meel\WhenFormats\Relative;

use Carbon\Carbon;

class RelativeNow extends RelativeWhenFormat
{
    protected $specifiesTime = true;

    public $isRelativeToNow = false;

    public function __construct(string $string, $timezone = null)
    {
        $this->isRelativeToNow = $string === 'now' || $string === 'right now';
    }

    public function isUsableMatch(): bool
    {
        return $this->isRelativeToNow;
    }

    public function transformNow(Carbon $carbon)
    {
        //
    }
}
