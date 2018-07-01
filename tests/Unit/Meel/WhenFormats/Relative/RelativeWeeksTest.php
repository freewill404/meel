<?php

namespace Tests\Unit\Meel\WhenFormats\Relative;

use App\Meel\WhenFormats\Relative\RelativeWeeks;

class RelativeWeeksTest extends RelativeWhenFormatTestCase
{
    protected $whenFormat = RelativeWeeks::class;

    protected $shouldMatch = [
        'next week',
        'in 1 month and 1 week',
        'in 2 weeks',
        '1 month and 2 week from now',
        '2 weeks and 1 day from now',
    ];

    protected $shouldNotMatch = [
        'last week',
        'in 0 weeks',
        '2 weeks',
    ];
}
