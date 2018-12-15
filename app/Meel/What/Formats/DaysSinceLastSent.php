<?php

namespace App\Meel\What\Formats;

use Carbon\Carbon;

class DaysSinceLastSent extends WhatFormat
{
    public function applyFormat(string $string): string
    {
        $regex = '/%d([+-]\d+)?/';

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

        $lastSent = $this->schedule->last_sent_at
            ? Carbon::parse($this->schedule->last_sent_at)->setTimezone($this->timezone)
            : null;

        $daysSinceLastSent = $lastSent
             ? $lastSent->diffInDays($now) + $offset
             : 'n/a';

        return preg_replace($regex, $daysSinceLastSent, $string);
    }
}
