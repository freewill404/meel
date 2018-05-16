<?php

namespace Tests\Unit;

use App\Meel\WhenString;
use Tests\TestCase;

class WhenStringTest extends TestCase
{
    private function assertPreparedInput($expected, $input)
    {
        $whenString = new WhenString($input);

        $this->assertSame(
            $expected,
            $actual = $whenString->getPreparedString(),
            "WhenString input '{$input}' was prepared as '{$actual}', should be '{$expected}'"
        );
    }

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
    function it_changes_recurring_text_to_single_words()
    {
        $this->assertPreparedInput('weekly', 'every week');
        $this->assertPreparedInput('monthly', 'every month');
        $this->assertPreparedInput('yearly', 'every year');

        $this->assertPreparedInput('weekly on monday',    'every monday');
        $this->assertPreparedInput('weekly on tuesday',   'every tuesday');
        $this->assertPreparedInput('weekly on wednesday', 'every wednesday');
        $this->assertPreparedInput('weekly on thursday',  'every thursday');
        $this->assertPreparedInput('weekly on friday',    'every friday');
        $this->assertPreparedInput('weekly on saturday',  'every saturday');
        $this->assertPreparedInput('weekly on sunday',    'every sunday');
    }
}
