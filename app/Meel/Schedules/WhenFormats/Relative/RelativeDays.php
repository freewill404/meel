<?php

namespace App\Meel\Schedules\WhenFormats\Relative;

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

        if (preg_match('/(?:^|next |this |on )(monday|tuesday|wednesday|thursday|friday|saturday|sunday)/', $string, $matches)) {
            return $this->daysUntilNext($matches[1], $timezone);
        }

        return 0;
    }

    protected function daysUntilNext(string $day, $timezone): int
    {
        $day = ucfirst($day);

        $carbon = now($timezone);

        for ($i = 1; $i <= 7; $i++) {
            $carbon->addDays(1);

            if ($carbon->format('l') === $day) {
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
