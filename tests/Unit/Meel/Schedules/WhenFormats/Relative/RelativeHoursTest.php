<?php

namespace Tests\Unit\Meel\Schedules\WhenFormats\Relative;

use App\Meel\Schedules\WhenFormats\Relative\RelativeHours;

class RelativeHoursTest extends RelativeWhenFormatTestCase
{
    protected $whenFormat = RelativeHours::class;

    protected $shouldMatch = [
        'in 1 hour',
        'in 1 day and 2 hours',
        '2 hour and 3 days from now',
        '1 week and 2 hours from now',
        '1 hour from now',
    ];

    protected $shouldNotMatch = [
        'in 0 hours',
        '2 hours',
    ];
}
