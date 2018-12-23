<?php

namespace Tests\Unit\Meel\When\Formats\Relative;

use App\Meel\When\Formats\Relative\RelativeWeeks;

class RelativeWeeksTest extends RelativeWhenFormatTestCase
{
    protected $whenFormat = RelativeWeeks::class;

    protected $shouldMatch = [
        // Tuesday
        '2018-05-15 12:00:00' => [
            'next week' => '2018-05-22 12:00:00',
            'in 1 weeks' => '2018-05-22 12:00:00',
            'in 2 weeks' => '2018-05-29 12:00:00',
            'in 3 weeks' => '2018-06-05 12:00:00',

            'in 1 month and 1 week' => '2018-05-22 12:00:00',
            '1 month and 2 week from now' => '2018-05-29 12:00:00',
            '2 weeks and 1 day from now' => '2018-05-29 12:00:00',
        ],
    ];

    protected $shouldNotMatch = [
        '2018-05-15 12:00:00' => [
            'last week',
            'in 0 weeks',
            '2 weeks',
        ],
    ];
}
