<?php

namespace App\Meel\WhenFormats\Recurring;

use App\Meel\DateTime\DateString;
use App\Meel\DateTime\TimeString;

abstract class RecurringWhenFormat
{
    abstract public function __construct(string $string);

    abstract public function isUsableMatch(): bool;

    abstract public function getNextDate(TimeString $setTime, $timezone = null): DateString;

    public static function matches(string $string): bool
    {
        $self = new static($string);

        return $self->isUsableMatch();
    }
}
