<?php

namespace Tests\Unit\Meel\WhenFormats;

use App\Meel\WhenFormats\TimeInterpretation;
use Tests\TestCase;

class TimeInterpretationTest extends TestCase
{
    /** @test */
    function it_interprets_valid_times()
    {
        $this->assertTimeInterpretation('02:45:00', '2:45');

        $this->assertTimeInterpretation('13:45:00', 'at 13:45');
    }

    /** @test */
    function it_does_not_interpret_invalid_times()
    {
        $this->assertNotUsable('at 25:00');
        $this->assertNotUsable('at 12:60');
        $this->assertNotUsable('at 12:61');
    }

    /** @test */
    function it_interprets_hours()
    {
        $this->assertTimeInterpretation('02:00:00', 'at 2');

        $this->assertTimeInterpretation('13:00:00', 'at 13');
    }

    private function assertTimeInterpretation($expected, $input)
    {
        $timeInterpretation = new TimeInterpretation($input);

        if (! $timeInterpretation->isUsableMatch()) {
            $this->fail("TimeInterpretation interpreted '{$input}' as not being a valid time, expected '{$expected}'");
        }

        $this->assertSame(
            $expected,
            $actual = (string) $timeInterpretation->getTimeString(),
            "TimeInterpretation interpreted '{$input}' as '{$actual}', expected '{$expected}'"
        );
    }

    private function assertNotUsable($input)
    {
        $timeInterpretation = new TimeInterpretation($input);

        $this->assertFalse(
            $timeInterpretation->isUsableMatch()
        );
    }
}
