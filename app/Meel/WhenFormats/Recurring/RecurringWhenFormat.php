<?php

namespace App\Meel\WhenFormats\Recurring;

use App\Support\DateTime\DateString;
use App\Support\DateTime\SecondlessTimeString;

abstract class RecurringWhenFormat
{
    abstract public function __construct(string $string);

    abstract public function isUsableMatch(): bool;

    abstract public function getNextDate(SecondlessTimeString $setTime, $timezone = null): DateString;

    abstract public function intervalDescription();

    public static function matches(string $string): bool
    {
        $self = new static($string);

        return $self->isUsableMatch();
    }
}
