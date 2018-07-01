<?php

namespace Tests\Unit\Meel\WhenFormats\Relative;

use App\Meel\WhenFormats\Relative\RelativeMinutes;

class RelativeMinutesTest extends RelativeWhenFormatTestCase
{
    protected $whenFormat = RelativeMinutes::class;

    protected $shouldMatch = [
        'in 1 minute',
        'in 2 minutes',
        'in 1 hour and 5 minutes',
        '2 minute and 3 days from now',
        '1 week and 2 minutes from now',
    ];

    protected $shouldNotMatch = [
        'in 0 minutes',
        '2 minutes',
    ];
}
