<?php

namespace App\Meel\When\Formats\Relative;

use Carbon\Carbon;

class RelativeNow extends RelativeWhenFormat
{
    protected $specifiesTime = true;

    public $isRelativeToNow = false;

    public function __construct($now, string $writtenInput)
    {
        $this->isRelativeToNow = $writtenInput === 'now' || $writtenInput === 'right now';
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
