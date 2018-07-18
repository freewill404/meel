<?php

namespace App\Meel\Schedules\WhenFormats\Relative;

use Carbon\Carbon;

abstract class RelativeWhenFormat
{
    protected $specifiesTime = false;

    abstract public function __construct(string $string, $timezone = null);

    abstract public function isUsableMatch(): bool;

    abstract public function transformNow(Carbon $carbon);

    public function specifiesTime(): bool
    {
        return $this->specifiesTime;
    }

    public static function matches(string $string): bool
    {
        $self = new static($string);

        return $self->isUsableMatch();
    }
}
