<?php

namespace App\Meel\Schedules\WhatFormats;

class DaysSinceCreation extends WhatFormat
{
    public function applyFormat(string $string): string
    {
        $regex = '/%a([+-]\d+)?/';

        if (! preg_match($regex, $string, $matches)) {
            return $string;
        }

        $offset = $matches[1] ?? 0;

        // "diffInDays" counts per 24 hours, setting the time to the
        // last second of the day causes the following behavior:
        //
        // 2018-03-28 18:00:00 - 2018-03-29 12:00:00 = 0 days difference
        // 2018-03-28 18:00:00 - 2018-03-29 23:59:59 = 1 day difference
        $now = now($this->timezone)->setTimeFromTimeString('23:59:59');

        $createdAt = $this->schedule->created_at->copy()->setTimezone($this->timezone);

        $daysSinceCreation = $createdAt->diffInDays($now) + $offset;

        return preg_replace($regex, $daysSinceCreation, $string);
    }
}
