<?php

namespace Tests\Unit\Meel\When\Formats\Relative;

use App\Meel\When\Formats\Relative\RelativeMonths;

class RelativeMonthsTest extends RelativeWhenFormatTestCase
{
    protected $whenFormat = RelativeMonths::class;

    protected $shouldMatch = [
        '2018-05-15 12:00:00' => [
            'next month' => '2018-06-15 12:00:00',
            'in 1 month' => '2018-06-15 12:00:00',
            'in 1 year and 2 months' => '2018-07-15 12:00:00',
            '2 month and 3 days from now' => '2018-07-15 12:00:00',
            '1 week and 2 months from now' => '2018-07-15 12:00:00',
        ],
    ];

    protected $shouldNotMatch = [
        '2018-05-15 12:00:00' => [
            'last month',
            'in 0 months',
            '2 months',
        ],
    ];
}
