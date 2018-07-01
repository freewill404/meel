<?php

namespace Tests\Unit\Meel\WhenFormats\Relative;

use App\Meel\WhenFormats\Relative\RelativeNow;

class RelativeNowTest extends RelativeWhenFormatTestCase
{
    protected $whenFormat = RelativeNow::class;

    protected $shouldMatch = [
        'now',
        'right now',
    ];

    protected $shouldNotMatch = [
        'nowadays',
        '1 month from now',
    ];

    /** @test */
    function it_does_not_transform()
    {
        $this->assertTransformedNow('2018-03-28 12:00:15', 'now');
    }
}
