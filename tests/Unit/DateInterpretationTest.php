<?php

namespace Tests\Unit;

use App\Meel\DateInterpretation;
use Tests\TestCase;

class DateInterpretationTest extends TestCase
{
    private function assertDateInterpretation($expected, $input)
    {
        $dateInterpretation = new DateInterpretation($input);

        if (! $dateInterpretation->isValidDate()) {
            $this->fail("DateInterpretation interpreted '{$input}' as not being a valid date, expected '{$expected}'");
        }

        $this->assertSame(
            $expected,
            $actual = $dateInterpretation->getDate()->format('Y-m-d'),
            "DateInterpretation interpreted '{$input}' as '{$actual}', expected '{$expected}'"
        );
    }

    /** @test */
    function it_interprets_valid_long_dates()
    {
        // d-m-Y
        $this->assertDateInterpretation('2018-03-28', '28-03-2018');
        $this->assertDateInterpretation('2018-03-28', 'On 28-03-2018');

        // unambiguous m-d-Y
        $this->assertDateInterpretation('2018-03-28', '3-28-2018');

        // ambiguous m-d-Y (in this case always assume d-m-Y)
        $this->assertDateInterpretation('2018-02-03', '03-02-2018');
    }

    /** @test */
    function it_interprets_valid_dates_without_a_year()
    {
        $currentYear = now()->format('Y');

        // d-m
        $this->assertDateInterpretation($currentYear.'-03-28', '28-03');
        $this->assertDateInterpretation($currentYear.'-03-28', 'On 28-03');

        // unambiguous m-d
        $this->assertDateInterpretation($currentYear.'-03-28', '03-28');

        // ambiguous m-d (in this case always assume d-m)
        $this->assertDateInterpretation($currentYear.'-02-03', '03-02');
    }
}
