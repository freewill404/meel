<?php

namespace Tests\Unit\Meel\When\Formats;

use App\Meel\When\Formats\TimeInterpretation;
use Tests\TestCase;

class TimeInterpretationTest extends TestCase
{
    private $testValues = [
        '2:45' => '02:45:00',
        '02:45' => '02:45:00',
        'at 13:45' => '13:45:00',
        'at 2' => '02:00:00',
        'at 13' => '13:00:00',
    ];

    private $notUsable = [
        'at 25:00',
        'at 12:60',
        'at 12:61',
    ];

    /** @test */
    function it_interprets_time()
    {
        foreach ($this->testValues as $writtenInput => $expected) {
            $timeInterpretation = new TimeInterpretation($writtenInput);

            if (! $timeInterpretation->isUsableMatch()) {
                $this->fail("TimeInterpretation interpreted '{$writtenInput}' as not being a valid time, expected '{$expected}'");
            }

            $this->assertSame(
                $expected,
                $actual = (string) $timeInterpretation->getTimeString(),
                "TimeInterpretation interpreted '{$writtenInput}' as '{$actual}', expected '{$expected}'"
            );
        }
    }

    /** @test */
    function it_does_not_interpret_unusable_values()
    {
        foreach ($this->notUsable as $writtenInput) {
            $timeInterpretation = new TimeInterpretation($writtenInput);

            $this->assertFalse(
                $timeInterpretation->isUsableMatch()
            );
        }
    }
}
