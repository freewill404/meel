<?php

namespace Tests\Unit\Meel\When\Formats\Relative;

use App\Meel\When\Formats\Relative\RelativeDays;

class RelativeDaysTest extends RelativeWhenFormatTestCase
{
    protected $whenFormat = RelativeDays::class;

    protected $shouldMatch = [
        // Tuesday
        '2018-05-15 12:00:00' => [
            'tomorrow' => '2018-05-16 12:00:00',
            'tomorrow at 17:00' => '2018-05-16 12:00:00',
            'in 1 day' => '2018-05-16 12:00:00',

            'in 1 week and 1 day' => '2018-05-16 12:00:00',
            '3 days and 1 hour from now' => '2018-05-18 12:00:00',
            'next monday' => '2018-05-21 12:00:00',

            'this wednesday' => '2018-05-16 12:00:00',
            'this thu' => '2018-05-17 12:00:00',
            'on saturday' => '2018-05-19 12:00:00',
            'on sun' => '2018-05-20 12:00:00',
            'monday' => '2018-05-21 12:00:00',
            'monday at 22:00' => '2018-05-21 12:00:00',

            'next tue' => '2018-05-22 12:00:00',
        ],

        '2018-03-28 12:00:15' => [
            'next saturday' => '2018-03-31 12:00:15',
            'this tues' => '2018-04-03 12:00:15',
            'on sunday' => '2018-04-01 12:00:15',
        ],
    ];

    protected $shouldNotMatch = [
        '2018-05-15 12:00:00' => [
            'yesterday',
            'in 0 days',
            '2 days',
        ]
    ];
}
