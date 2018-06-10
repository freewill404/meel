<?php

namespace App\Meel\WhenFormats\Recurring;

use App\Support\DateTime\DateString;
use App\Support\DateTime\SecondlessTimeString;

abstract class RecurringWhenFormat
{
    protected $usableMatch = false;

    protected $intervalDescription = 'TODO';

    abstract public function __construct(string $string);

    public function isUsableMatch(): bool
    {
        return (bool) $this->usableMatch;
    }

    public function intervalDescription(): string
    {
        return $this->intervalDescription;
    }

    abstract public function getNextDate(SecondlessTimeString $setTime, $timezone = null): DateString;

    public static function matches(string $string): bool
    {
        $self = new static($string);

        return $self->isUsableMatch();
    }
}
