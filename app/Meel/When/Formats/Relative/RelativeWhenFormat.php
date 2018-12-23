<?php

namespace App\Meel\When\Formats\Relative;

use Carbon\Carbon;

abstract class RelativeWhenFormat
{
    protected $specifiesTime = false;

    abstract public function __construct($now, string $writtenInput);

    abstract public function isUsableMatch(): bool;

    abstract public function transformNow(Carbon $carbon);

    public function specifiesTime(): bool
    {
        return $this->specifiesTime;
    }

//    public static function matches(string $string): bool
//    {
//        $self = new static($string);
//
//        return $self->isUsableMatch();
//    }
}
