<?php

namespace App\Meel\When\Formats;

use App\Support\DateTime\DateString;
use App\Support\DateTime\SecondlessTimeString;
use App\Meel\When\Formats\Recurring\RecurringWhenFormat;
use Carbon\Carbon;
use RuntimeException;

class RecurringInterpretation
{
    protected $formats = [
        Recurring\MonthlyNthDay::class,
        Recurring\Yearly::class,
        Recurring\Monthly::class,
        Recurring\Weekly::class,
        Recurring\Daily::class,
        Recurring\GivenDays::class,
    ];

    protected $matchedFormat = null;

    protected $now;

    public function __construct($now, string $writtenInput)
    {
        $this->now = $now instanceof Carbon
            ? $now->copy()
            : Carbon::parse($now);

        foreach ($this->formats as $recurringWhenFormat) {
            /** @var RecurringWhenFormat $format */
            $format = new $recurringWhenFormat($writtenInput);

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

    public function getNextDate(SecondlessTimeString $setTime): DateString
    {
        if (! $this->isUsableMatch()) {
            throw new RuntimeException('Not a usable match');
        }

        return $this->matchedFormat->getNextDate($this->now->copy(), $setTime);
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
