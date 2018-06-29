<?php

namespace App\Meel\WhenFormats\Relative;

use App\Support\Enums\Days;
use Carbon\Carbon;
use LogicException;

class RelativeDays extends RelativeWhenFormat
{
    public $days = 0;

    public function __construct(string $string, $timezone = null)
    {
        $this->days = $this->parseDays($string, $timezone);
    }

    protected function parseDays($string, $timezone)
    {
        if (strpos($string, 'tomorrow') !== false) {
            return 1;
        }

        if (preg_match('/(\d+) days? .*from now/', $string, $matches)) {
            return (int) $matches[1];
        }

        if (preg_match('/in (\d+) days?/', $string, $matches)) {
            return (int) $matches[1];
        }

        if (preg_match('/in .*and (\d+) days?/', $string, $matches)) {
            return (int) $matches[1];
        }

        if (preg_match('/(?:next |this |on |^)'.Days::regex().'/', $string, $matches)) {
            return $this->daysUntilNext($matches[1], $timezone);
        }

        return 0;
    }

    protected function daysUntilNext(string $day, $timezone): int
    {
        $dayInt = Days::toInt($day);

        $carbon = now($timezone);

        for ($i = 1; $i <= 7; $i++) {
            $carbon->addDays(1);

            if ($carbon->dayOfWeek === $dayInt) {
                return $i;
            }
        }

        throw new LogicException();
    }

    public function isUsableMatch(): bool
    {
        return (bool) $this->days;
    }

    public function transformNow(Carbon $carbon)
    {
        $carbon->addDays($this->days);
    }
}
