<?php

namespace App\Meel\WhenFormats;

use App\Support\DateTime\DateString;
use App\Support\DateTime\TimeString;
use App\Meel\WhenFormats\Recurring\RecurringWhenFormat;
use RuntimeException;

class RecurringInterpretation
{
    protected $formats = [
        Recurring\MonthlyNthDay::class,
        Recurring\Yearly::class,
        Recurring\Monthly::class,
        Recurring\Weekly::class,
    ];

    protected $matchedFormat = null;

    public function __construct(string $string)
    {
        foreach ($this->formats as $recurringWhenFormat) {
            /** @var RecurringWhenFormat $format */
            $format = new $recurringWhenFormat($string);

            if ($format->isUsableMatch()) {
                $this->matchedFormat = $format;

                break;
            }
        }
    }

    public function isUsableMatch(): bool
    {
        return $this->matchedFormat !== null;
    }

    public function getNextDate(TimeString $setTime, $timezone): DateString
    {
        if (! $this->isUsableMatch()) {
            throw new RuntimeException('Not a usable match');
        }

        return $this->matchedFormat->getNextDate($setTime, $timezone);
    }

    public function getMatchedFormat(): RecurringWhenFormat
    {
        if (! $this->isUsableMatch()) {
            throw new RuntimeException('Not a usable match');
        }

        return $this->matchedFormat;
    }

    public function intervalDescription()
    {
        return $this->matchedFormat->intervalDescription();
    }
}
