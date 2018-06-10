<?php

namespace Tests\Unit\WhenFormats\Relative;

use App\Meel\WhenFormats\Relative\RelativeDays;

class RelativeDaysTest extends RelativeWhenFormatTestCase
{
    protected $whenFormat = RelativeDays::class;

    protected $shouldMatch = [
        'tomorrow',
        'tomorrow at 17:00',
        'in 1 day',
        'in 1 week and 1 day',
        '3 days and 1 hour from now',
        'next monday',
        'next tue',
    ];

    protected $shouldNotMatch = [
        'yesterday',
        'in 0 days',
        '2 days',
    ];

    /** @test */
    function it_interprets_written_days()
    {
        $this->assertTransformedNow('2018-03-31 12:00:15', 'next saturday');

        $this->assertTransformedNow('2018-04-03 12:00:15', 'next tues');
    }
}
