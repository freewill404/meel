<?php

namespace Tests\Unit\WhenFormats;

use App\Meel\WhenFormats\RelativeToNowInterpretation;
use Carbon\Carbon;
use Tests\TestCase;

class RelativeToNowInterpretationTest extends TestCase
{
    private function assertIsRelativeToNow($input)
    {
        $relativeNow = new RelativeToNowInterpretation($input);

        $this->assertTrue(
            $relativeNow->isRelativeToNow(),
            "RelativeToNowInterpretation did not interpret '{$input}' to be relative to now"
        );
    }

    private function assertIsNotRelativeToNow($input)
    {
        $relativeNow = new RelativeToNowInterpretation($input);

        $this->assertFalse(
            $relativeNow->isRelativeToNow(),
            "RelativeToNowInterpretation interpreted '{$input}' to be relative to now"
        );
    }

    private function assertRelativeNow($expected, $input)
    {
        $relativeNow = new RelativeToNowInterpretation($input);

        if (! $relativeNow->isRelativeToNow()) {
            $this->fail("RelativeToNowInterpretation interpreted '{$input}' as not being relative to now, should be '{$expected}'");
        }

        $this->assertSame(
            $expected,
            $actual = $relativeNow->getTime()->format('Y-m-d H:i:s'),

            "\nWrong RelativeToNowInterpretation\n Input:    '{$input}'\n Expected: '{$expected}' ".
            Carbon::parse($expected)->format('l').
            "\n Actual:   '{$actual}' ".
            Carbon::parse($actual)->format('l').
            "\n\n Current now: ".now()." ".now()->format('l')."\n"
        );
    }

    /** @test */
    function it_interprets_when_a_string_is_relative_to_now()
    {
        $this->assertIsRelativeToNow('now');
        $this->assertIsRelativeToNow('right now');
        $this->assertIsRelativeToNow('an hour from now');
        $this->assertIsRelativeToNow('in two hours');
        $this->assertIsRelativeToNow('next month');
        $this->assertIsRelativeToNow('at 5');
    }

    /** @test */
    function it_interprets_when_a_string_is_not_relative_to_now()
    {
        $this->assertIsNotRelativeToNow('1 hour');
        $this->assertIsNotRelativeToNow('24th of may 2018');
    }

    /** @test */
    function it_interprets_now()
    {
        $this->assertRelativeNow('2018-03-28 12:01:00', 'now');

        $this->assertRelativeNow('2018-03-28 12:01:00', 'right now');
    }

    /** @test */
    function it_interprets_years()
    {
        $this->assertRelativeNow('2019-03-28 08:00:00', 'next year');

        $this->assertRelativeNow('2019-03-28 08:00:00', 'in 1 year');

        $this->assertRelativeNow('2021-03-28 08:00:00', 'in 3 years');
    }

    /** @test */
    function it_interprets_months()
    {
        $this->assertRelativeNow('2018-04-28 08:00:00', 'next month');

        $this->assertRelativeNow('2018-04-28 08:00:00', 'in 1 month');

        $this->assertRelativeNow('2018-06-28 08:00:00', '3 months from now');
    }

    /** @test */
    function it_interprets_weeks()
    {
        $this->assertRelativeNow('2018-04-04 08:00:00', 'next week');

        $this->assertRelativeNow('2018-04-18 08:00:00', '3 weeks from now');
    }

    /** @test */
    function it_interprets_days()
    {
        $this->assertRelativeNow('2018-03-29 08:00:00', 'tomorrow');

        $this->assertRelativeNow('2018-03-29 08:00:00', 'tomorrow at 17:00');

        $this->assertRelativeNow('2018-03-29 08:00:00', 'in 1 day');

        $this->assertRelativeNow('2018-03-31 08:00:00', '3 days from now');
    }

    /** @test */
    function it_interprets_hours_and_minutes()
    {
        $this->assertRelativeNow('2018-03-28 15:00:00', '3 hours from now');

        $this->assertRelativeNow('2018-03-28 12:03:00', '3 minutes from now');

        $this->assertRelativeNow('2018-03-28 14:15:00', '2 hours and 15 minutes from now');
    }

    /** @test */
    function it_uses_the_default_time_if_no_time_is_specified()
    {
        $this->assertRelativeNow('2018-03-28 13:00:00', 'in 1 hour');

        $this->assertRelativeNow('2018-04-04 08:00:00', 'in 1 week');
    }

    /** @test */
    function it_interprets_basic_sentences()
    {
        $this->assertRelativeNow('2018-03-31 08:00:00', 'next saturday');
    }
}
