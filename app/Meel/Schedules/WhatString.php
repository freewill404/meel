<?php

namespace App\Meel\Schedules;

use App\Meel\Schedules\WhatFormats\WhatFormat;
use App\Models\EmailSchedule;

class WhatString
{
    protected $formattedString;

    protected $formats = [
        WhatFormats\DateFormat::class,
        WhatFormats\DaysSinceCreation::class,
        WhatFormats\DaysSinceLastSent::class,
        WhatFormats\TimesSent::class,
    ];

    public function __construct(EmailSchedule $emailSchedule)
    {
        // Encode urls to prevent urls with percentage signs
        // having a WhatFormat applied to them.
        $string = $this->encodeUrls($emailSchedule->what);

        foreach ($this->formats as $format) {
            /** @var WhatFormat $whatFormat */
            $whatFormat = new $format($emailSchedule);

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

    public static function format(EmailSchedule $emailSchedule): string
    {
        $whatString = new static($emailSchedule);

        return $whatString->getFormattedString();
    }
}
