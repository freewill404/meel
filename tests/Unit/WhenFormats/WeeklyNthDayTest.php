<?php

namespace Tests\Unit\WhenFormats;

use App\Meel\WhenString;
use App\Meel\WhenFormats\WeeklyNthDay;
use Tests\TestCase;

class WeeklyNthDayTest extends TestCase
{
    private function assertWhenFormatMatches($string)
    {
        $preparedString = WhenString::prepare($string);

        $this->assertTrue(
            WeeklyNthDay::matches($preparedString),
            'WeeklyNthDay did not match string: "'.$preparedString.'"'
        );
    }

    /** @test */
    function it_matches()
    {
        $this->assertWhenFormatMatches('every 3rd saturday of the month');

        $this->assertWhenFormatMatches('every 4th saturday of the month');

        $this->assertWhenFormatMatches('the last monday of the month');
    }
}
