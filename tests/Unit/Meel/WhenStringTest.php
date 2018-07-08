<?php

namespace Tests\Unit\Meel;

use App\Meel\WhenString;
use Tests\TestCase;

class WhenStringTest extends TestCase
{
    /** @test */
    function it_trims_input()
    {
        $this->assertPreparedInput('now', ' now ');
    }

    /** @test */
    function it_converts_input_to_lowercase()
    {
        $this->assertPreparedInput('now', 'Now');
    }

    /** @test */
    function it_changes_a_and_an_to_the_number_one()
    {
        $this->assertPreparedInput('in 1 hour', 'in an hour');

        $this->assertPreparedInput('1 hour from now', 'an hour from now');

        $this->assertPreparedInput('in 1 minute', 'in a minute');

        $this->assertPreparedInput('in 1 hour and 1 minute', 'in an hour and a minute');

        // Only replace when "a" or "an" are not part of a word
        $this->assertPreparedInput('anders kepa kepan dana', 'anders kepa kepan dana');
    }

    /** @test */
    function it_changes_written_numbers_to_actual_numbers()
    {
        $this->assertPreparedInput('in 1 hour', 'in one hour');
        $this->assertPreparedInput('in 2 hours', 'in two hours');
        $this->assertPreparedInput('in 3 hours', 'in three hours');
        $this->assertPreparedInput('in 4 hours', 'in four hours');
        $this->assertPreparedInput('in 5 hours', 'in five hours');
        $this->assertPreparedInput('in 6 hours', 'in six hours');
        $this->assertPreparedInput('in 7 hours', 'in seven hours');
        $this->assertPreparedInput('in 8 hours', 'in eight hours');
        $this->assertPreparedInput('in 9 hours', 'in nine hours');
        $this->assertPreparedInput('in 10 hours', 'in ten hours');
        $this->assertPreparedInput('in 15 minutes', 'in fifteen minutes');
        $this->assertPreparedInput('in 30 minutes', 'in thirty minutes');
        $this->assertPreparedInput('in 60 minutes', 'in sixty minutes');
    }

    /** @test */
    function it_changes_yearly_text()
    {
        $this->assertPreparedInput('every 1 year', 'yearly');
        $this->assertPreparedInput('every 1 year', 'every year');
    }

    /** @test */
    function it_changes_monthly_text()
    {
        $this->assertPreparedInput('every 1 month', 'every month');
        $this->assertPreparedInput('every 1 month', 'monthly');
        $this->assertPreparedInput('every 3 months', 'every 3 months');

        $this->assertPreparedInput('every 2 month', 'bimonthly');
        $this->assertPreparedInput('every 2 month', 'bi monthly');
        $this->assertPreparedInput('every 2 month', 'bi-monthly');
    }

    /** @test */
    function it_changes_weekly_text()
    {
        $this->assertPreparedInput('every 1 week', 'weekly');
        $this->assertPreparedInput('every 1 week', 'every week');

        $this->assertPreparedInput('every 2 week', 'biweekly');
        $this->assertPreparedInput('every 2 week', 'bi weekly');
        $this->assertPreparedInput('every 2 week', 'bi-weekly');

        $this->assertPreparedInput('every 1 week on monday',    'every monday');
        $this->assertPreparedInput('every 1 week on tuesday',   'every tuesday');
        $this->assertPreparedInput('every 1 week on wednesday', 'every wednesday');
        $this->assertPreparedInput('every 1 week on thursday',  'every thursday');
        $this->assertPreparedInput('every 1 week on friday',    'every friday');
        $this->assertPreparedInput('every 1 week on saturday',  'every saturday');
        $this->assertPreparedInput('every 1 week on sunday',    'every sunday');
    }

    /** @test */
    function it_changes_short_days_to_full_days()
    {
        $this->assertPreparedInput('on monday',    'on mon');
        $this->assertPreparedInput('on tuesday',   'on tue');
        $this->assertPreparedInput('on tuesday',   'on tues');
        $this->assertPreparedInput('on wednesday', 'on wed');
        $this->assertPreparedInput('on thursday',  'on thu');
        $this->assertPreparedInput('on thursday',  'on thur');
        $this->assertPreparedInput('on thursday',  'on thurs');
        $this->assertPreparedInput('on friday',    'on fri');
        $this->assertPreparedInput('on saturday',  'on sat');
        $this->assertPreparedInput('on sunday',    'on sun');
    }

    /** @test */
    function it_changes_short_months_to_full_months()
    {
        $this->assertPreparedInput('in january',   'in jan');
        $this->assertPreparedInput('in february',  'in feb');
        $this->assertPreparedInput('in march',     'in mar');
        $this->assertPreparedInput('in april',     'in apr');
        $this->assertPreparedInput('in june',      'in jun');
        $this->assertPreparedInput('in july',      'in jul');
        $this->assertPreparedInput('in august',    'in aug');
        $this->assertPreparedInput('in september', 'in sep');
        $this->assertPreparedInput('in september', 'in sept');
        $this->assertPreparedInput('in october',   'in oct');
        $this->assertPreparedInput('in november',  'in nov');
        $this->assertPreparedInput('in december',  'in dec');
    }

    private function assertPreparedInput($expected, $input)
    {
        $whenString = new WhenString($input);

        $this->assertSame(
            $expected,
            $actual = $whenString->getPreparedString(),
            "WhenString input '{$input}' was prepared as '{$actual}', expected '{$expected}'"
        );
    }
}
