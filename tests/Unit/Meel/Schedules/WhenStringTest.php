<?php

namespace Tests\Unit\Meel\Schedules;

use App\Meel\Schedules\WhenString;
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
    function it_replaces_commas()
    {
        $this->assertPreparedInput('the d and e and f', 'the d, e and f');

        $this->assertPreparedInput('the d and e and f', 'the d,e,f');

        $this->assertPreparedInput('the d and e and f and', 'the d,e,f,');

        $this->assertPreparedInput('the d and and b', 'the d,,b');

        $this->assertPreparedInput('every monday and tuesday and thursday and friday', 'every mon, tues, thurs,fri');
    }

    /** @test */
    function it_changes_written_numbers_to_actual_numbers()
    {
        $this->assertPreparedInput([
            'in one hour'        => 'in 1 hour',
            'in two hours'       => 'in 2 hours',
            'in three hours'     => 'in 3 hours',
            'in four hours'      => 'in 4 hours',
            'in five hours'      => 'in 5 hours',
            'in six hours'       => 'in 6 hours',
            'in seven hours'     => 'in 7 hours',
            'in eight hours'     => 'in 8 hours',
            'in nine hours'      => 'in 9 hours',
            'in ten hours'       => 'in 10 hours',
            'in fifteen minutes' => 'in 15 minutes',
            'in thirty minutes'  => 'in 30 minutes',
            'in sixty minutes'   => 'in 60 minutes',
        ]);
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
    }

    /** @test */
    function it_changes_short_days_to_full_days()
    {
        $this->assertPreparedInput([
            'on mon'   => 'on monday',
            'on tue'   => 'on tuesday',
            'on tues'  => 'on tuesday',
            'on wed'   => 'on wednesday',
            'on thu'   => 'on thursday',
            'on thur'  => 'on thursday',
            'on thurs' => 'on thursday',
            'on fri'   => 'on friday',
            'on sat'   => 'on saturday',
            'on sun'   => 'on sunday',
        ]);
    }

    /** @test */
    function it_changes_short_months_to_full_months()
    {
        $this->assertPreparedInput([
            'in jan'  => 'in january',
            'in feb'  => 'in february',
            'in mar'  => 'in march',
            'in apr'  => 'in april',
            'in jun'  => 'in june',
            'in jul'  => 'in july',
            'in aug'  => 'in august',
            'in sep'  => 'in september',
            'in sept' => 'in september',
            'in oct'  => 'in october',
            'in nov'  => 'in november',
            'in dec'  => 'in december',
        ]);
    }

    private function assertPreparedInput($expected, $input = null)
    {
        if (is_array($expected)) {
            foreach ($expected as $key => $value) {
                $this->assertPreparedInput($value, $key);
            }

            return;
        }

        $whenString = new WhenString($input);

        $this->assertSame(
            $expected,
            $actual = $whenString->getPreparedString(),
            "WhenString input '{$input}' was prepared as '{$actual}', expected '{$expected}'"
        );
    }
}
