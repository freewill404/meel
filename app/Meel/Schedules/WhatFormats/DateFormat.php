<?php

namespace App\Meel\Schedules\WhatFormats;

use Exception;

class DateFormat extends WhatFormat
{
    public function applyFormat(string $string): string
    {
        $regex = '/%f\[(?<format>.+?)\]/';

        if (! preg_match($regex, $string, $matches)) {
            return $string;
        }

        try {
            $formattedNow = now($this->timezone)->format($matches['format']);
        } catch (Exception $e) {
            $formattedNow = 'error';
        }

        return preg_replace($regex, $formattedNow, $string);
    }
}
