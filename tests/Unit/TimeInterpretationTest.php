<?php

namespace Tests\Unit;

use App\Meel\TimeInterpretation;
use Tests\TestCase;

class TimeInterpretationTest extends TestCase
{
    private function assertTimeInterpretation($expected, $input)
    {
        $timeInterpretation = new TimeInterpretation($input);

        if (! $timeInterpretation->isValidTime()) {
            $this->fail("TimeInterpretation interpreted '{$input}' as not being a valid time, expected '{$expected}'");
        }

        $this->assertSame(
            $expected,
            $actual = $timeInterpretation->getTime()->format('H:i:s'),
            "TimeInterpretation interpreted '{$input}' as '{$actual}', expected '{$expected}'"
        );
    }

    /** @test */
    function it_interprets_valid_times()
    {
        $this->assertTimeInterpretation('02:45:00', '2:45');

        $this->assertTimeInterpretation('13:45:00', 'at 13:45');
    }

    /** @test */
    function it_interprets_hours()
    {
        $this->assertTimeInterpretation('02:00:00', 'at 2');

        $this->assertTimeInterpretation('13:45:00', 'at 13:45');
    }
}
