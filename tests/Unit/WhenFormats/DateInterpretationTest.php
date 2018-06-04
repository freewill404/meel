<?php

namespace Tests\Unit\WhenFormats;

use App\Meel\WhenFormats\DateInterpretation;
use App\Meel\WhenString;
use Tests\TestCase;

class DateInterpretationTest extends TestCase
{
    /** @test */
    function it_interprets_valid_long_dates()
    {
        // d-m-Y
        $this->assertDateInterpretation('2018-03-28', '28-03-2018');
        $dateInterpretation = $this->assertDateInterpretation('2018-03-28', 'On 28-03-2018');

        $this->assertTrue($dateInterpretation->hasSpecifiedYear());
        $this->assertTrue($dateInterpretation->hasSpecifiedMonth());
        $this->assertTrue($dateInterpretation->hasSpecifiedDay());

        // unambiguous m-d-Y
        $this->assertDateInterpretation('2018-03-28', '3-28-2018');

        // ambiguous m-d-Y (in this case always assume d-m-Y)
        $this->assertDateInterpretation('2018-02-03', '03-02-2018');
    }

    /** @test */
    function it_interprets_valid_dates_without_a_year()
    {
        // d-m
        $this->assertDateInterpretation('2018-03-28', '28-03');
        $dateInterpretation = $this->assertDateInterpretation('2018-03-28', 'On 28-03');

        $this->assertFalse($dateInterpretation->hasSpecifiedYear());
        $this->assertTrue($dateInterpretation->hasSpecifiedMonth());
        $this->assertTrue($dateInterpretation->hasSpecifiedDay());

        // unambiguous m-d
        $this->assertDateInterpretation('2018-03-28', '03-28');

        // ambiguous m-d (in this case always assume d-m)
        $this->assertDateInterpretation('2018-02-03', '03-02');
    }

    /** @test */
    function it_interprets_years()
    {
        $dateInterpretation = $this->assertDateInterpretation('2020-01-01', 'in the year 2020');

        $this->assertTrue($dateInterpretation->hasSpecifiedYear());
        $this->assertFalse($dateInterpretation->hasSpecifiedMonth());
        $this->assertFalse($dateInterpretation->hasSpecifiedDay());
    }

    private function assertDateInterpretation($expected, $string)
    {
        $preparedString = WhenString::prepare($string);

        $dateInterpretation = new DateInterpretation($preparedString);

        if (! $dateInterpretation->hasSpecifiedDate()) {
            $this->fail("DateInterpretation interpreted '{$string}' as not having a specified date, expected '{$expected}'");
        }

        $this->assertSame(
            $expected,
            $actual = (string) $dateInterpretation->getDateString(),
            "DateInterpretation interpreted '{$string}' as '{$actual}', expected '{$expected}'"
        );

        return $dateInterpretation;
    }
}
