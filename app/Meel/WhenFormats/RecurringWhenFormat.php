<?php

namespace App\Meel\WhenFormats;

abstract class RecurringWhenFormat
{
    abstract public function __construct(string $string);

    abstract public function isUsableMatch(): bool;

    abstract public function getNextDate($setTime, $timezone);

    public static function matches(string $string): bool
    {
        $self = new static($string);

        return $self->isUsableMatch();
    }
}
