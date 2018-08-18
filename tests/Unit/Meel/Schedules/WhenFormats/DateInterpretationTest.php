<?php

namespace Tests\Unit\Meel\Schedules\WhenFormats;

use App\Meel\Schedules\WhenFormats\DateInterpretation;
use App\Meel\Schedules\WhenString;
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
        // also, this date is in the past and doesn't specify a year, so it sets the next year
        $this->assertDateInterpretation('2019-02-03', '03-02');
    }

    /** @test */
    function if_a_date_is_in_the_past_and_does_not_specify_a_year_it_sets_next_year()
    {
        $this->assertDateInterpretation('2019-01-31', '31-01');

        $this->assertDateInterpretation('2018-01-31', '31-01-2018');
    }

    /** @test */
    function it_interprets_years()
    {
        $dateInterpretation = $this->assertDateInterpretation('2020-01-01', 'in the year 2020');

        $this->assertTrue($dateInterpretation->hasSpecifiedYear());
        $this->assertFalse($dateInterpretation->hasSpecifiedMonth());
        $this->assertFalse($dateInterpretation->hasSpecifiedDay());
    }

    /** @test */
    function it_handles_the_amount_of_days_in_a_month()
    {
        $this->assertNotUsable('31-06');
        $this->assertNotUsable('32-05');
        $this->assertNotUsable('00-06');
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

    private function assertNotUsable($string)
    {
        $preparedString = WhenString::prepare($string);

        $dateInterpretation = new DateInterpretation($preparedString);

        $this->assertFalse(
            $dateInterpretation->hasSpecifiedDate()
        );
    }
}
