<?php

namespace Tests\Unit\Meel\When\Formats\Relative;

use App\Meel\When\Formats\Relative\RelativeMinutes;

class RelativeMinutesTest extends RelativeWhenFormatTestCase
{
    protected $whenFormat = RelativeMinutes::class;

    protected $shouldMatch = [
        '2018-05-15 12:00:00' => [
            'in 1 minute' => '2018-05-15 12:01:00',
            'in 2 minutes' => '2018-05-15 12:02:00',
            'in 1 hour and 5 minutes' => '2018-05-15 12:05:00',
            '2 minute and 3 days from now' => '2018-05-15 12:02:00',
            '1 week and 2 minutes from now' => '2018-05-15 12:02:00',
        ],
    ];

    protected $shouldNotMatch = [
        '2018-05-15 12:00:00' => [
            'in 0 minutes',
            '2 minutes',
        ],
    ];
}
