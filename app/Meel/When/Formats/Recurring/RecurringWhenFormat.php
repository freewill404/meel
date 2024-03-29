<?php

namespace App\Meel\When\Formats\Recurring;

use App\Support\DateTime\DateString;
use App\Support\DateTime\SecondlessTimeString;
use Carbon\Carbon;

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

    abstract public function getNextDate(Carbon $now, SecondlessTimeString $setTime): DateString;

    public static function matches(string $string): bool
    {
        $self = new static($string);

        return $self->isUsableMatch();
    }
}
