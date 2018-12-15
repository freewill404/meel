<?php

namespace App\Meel\What\Formats;

class TimesSent extends WhatFormat
{
    public function applyFormat(string $string): string
    {
        $regex = '/%t([+-]\d+)?/';

        if (! preg_match($regex, $string, $matches)) {
            return $string;
        }

        // Always add 1 to the "times_sent" because this
        // format is applied before the email is sent.
        $offset = 1 + ($matches[1] ?? 0);

        $timesSent = $this->schedule->times_sent + $offset;

        return preg_replace($regex, $timesSent, $string);
    }
}
