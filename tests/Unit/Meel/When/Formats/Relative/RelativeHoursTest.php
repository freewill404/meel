<?php

namespace Tests\Unit\Meel\When\Formats\Relative;

use App\Meel\When\Formats\Relative\RelativeHours;

class RelativeHoursTest extends RelativeWhenFormatTestCase
{
    protected $whenFormat = RelativeHours::class;

    protected $shouldMatch = [
        '2018-05-15 12:00:00' => [
            '1 hour from now' => '2018-05-15 13:00:00',
            'in 1 hour' => '2018-05-15 13:00:00',
            'in 1 day and 2 hours' => '2018-05-15 14:00:00',
            '2 hour and 3 days from now' => '2018-05-15 14:00:00',
            '1 week and 2 hours from now' => '2018-05-15 14:00:00',
        ],
    ];

    protected $shouldNotMatch = [
        '2018-05-15 12:00:00' => [
            'in 0 hours',
            '2 hours',
        ],
    ];
}
