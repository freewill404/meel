<?php

namespace App\Meel\What;

use App\Meel\What\Formats\WhatFormat;
use App\Models\Schedule;

class WhatString
{
    protected $formattedString;

    protected $formats = [
        Formats\DateFormat::class,
        Formats\DaysSinceCreation::class,
        Formats\DaysSinceLastSent::class,
        Formats\TimesSent::class,
    ];

    public function __construct(Schedule $schedule)
    {
        // Encode urls to prevent urls with percentage signs
        // having a WhatFormat applied to them.
        $string = $this->encodeUrls($schedule->what);

        foreach ($this->formats as $format) {
            /** @var WhatFormat $whatFormat */
            $whatFormat = new $format($schedule);

            $string = $whatFormat->applyFormat($string);
        }

        $this->formattedString = $this->decodeUrls($string);
    }

    protected function encodeUrls($string)
    {
        return preg_replace_callback('~(^| )(https?://[^ ]+)~i', function ($matches) {
            return $matches[1].str_replace('%', '%25', $matches[2]);
        }, $string);
    }

    protected function decodeUrls($string)
    {
        return preg_replace_callback('~(^| )(https?://[^ ]+)~i', function ($matches) {
            return $matches[1].str_replace('%25', '%', $matches[2]);
        }, $string);
    }

    public function getFormattedString()
    {
        return $this->formattedString;
    }

    public static function format(Schedule $schedule): string
    {
        $whatString = new static($schedule);

        return $whatString->getFormattedString();
    }
}
