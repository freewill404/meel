<?php

namespace App\Meel\When\Formats\Relative;

use Carbon\Carbon;
use RuntimeException;

class RelativeDays extends RelativeWhenFormat
{
    public $days = 0;

    public function __construct($now, string $writtenInput)
    {
        $now = Carbon::parse((string) $now);

        $this->days = $this->parseDays($now, $writtenInput);
    }

    protected function parseDays(Carbon $now, $writtenInput)
    {
        if (strpos($writtenInput, 'tomorrow') !== false) {
            return 1;
        }

        if (preg_match('/(\d+) days? .*from now/', $writtenInput, $matches)) {
            return (int) $matches[1];
        }

        if (preg_match('/in (\d+) days?/', $writtenInput, $matches)) {
            return (int) $matches[1];
        }

        if (preg_match('/in .*and (\d+) days?/', $writtenInput, $matches)) {
            return (int) $matches[1];
        }

        if (preg_match('/(?:^|next |this |on )(monday|tuesday|wednesday|thursday|friday|saturday|sunday)/', $writtenInput, $matches)) {
            return $this->daysUntilNext($now, $matches[1]);
        }

        return 0;
    }

    protected function daysUntilNext(Carbon $now, string $day): int
    {
        $day = ucfirst($day);

        for ($i = 1; $i <= 7; $i++) {
            $now->addDays(1);

            if ($now->format('l') === $day) {
                return $i;
            }
        }

        throw new RuntimeException();
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
