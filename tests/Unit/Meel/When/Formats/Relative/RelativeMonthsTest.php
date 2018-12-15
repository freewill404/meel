<?php

namespace Tests\Unit\Meel\When\Formats\Relative;

use App\Meel\When\Formats\Relative\RelativeMonths;

class RelativeMonthsTest extends RelativeWhenFormatTestCase
{
    protected $whenFormat = RelativeMonths::class;

    protected $shouldMatch = [
        'next month',
        'in 1 month',
        'in 1 year and 2 months',
        '2 month and 3 days from now',
        '1 week and 2 months from now',
    ];

    protected $shouldNotMatch = [
        'last month',
        'in 0 months',
        '2 months',
    ];
}
