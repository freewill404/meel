<?php

namespace Tests\Integration;

use App\Meel\EmailScheduleFormat;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class EmailScheduleFormatTest extends TestCase
{
    private function assertSingleOccurrenceSchedule(string $writtenInput, string $expectedNextOccurrence)
    {
        $scheduleFormat = new EmailScheduleFormat($writtenInput);

        $this->assertTrue(
            $scheduleFormat->isUsableInterpretation(),
            '"'.$writtenInput.'" was not interpreted as a usable interpretation'
        );

        $this->assertFalse(
            $scheduleFormat->isRecurring(),
            "'{$writtenInput}' was interpreted as recurring, it should not be"
        );

        $this->assertSame(
            $expectedNextOccurrence,
            $actual = $scheduleFormat->nextOccurrence(),
            "'{$writtenInput}' next occurrence was '{$actual}', should be '{$expectedNextOccurrence}'"
        );
    }

    private function assertRecurringSchedule(string $writtenInput, string $expectedNextOccurrence)
    {
        $scheduleFormat = new EmailScheduleFormat($writtenInput);

        $this->assertTrue(
            $scheduleFormat->isUsableInterpretation(),
            '"'.$writtenInput.'" was not interpreted as a usable interpretation'
        );

        $this->assertTrue(
            $scheduleFormat->isRecurring(),
            "'{$writtenInput}' was not interpreted as recurring, it should be"
        );

        $this->assertSame(
            $expectedNextOccurrence,
            $actual = $scheduleFormat->nextOccurrence(),
            "'{$writtenInput}' next occurrence was '{$actual}', should be '{$expectedNextOccurrence}'"
        );
    }

    /** @test */
    function it_determines_the_next_occurrence_for_non_recurring_schedules()
    {
        $this->assertSingleOccurrenceSchedule('now', '2018-03-28 12:01:00');

        $this->assertSingleOccurrenceSchedule('at 5', '2018-03-28 05:00:00');

        $this->assertSingleOccurrenceSchedule('tomorrow at 17', '2018-03-29 17:00:00');
    }

    /** @test */
    function it_determines_the_next_occurrence_for_weekly_recurring_schedules()
    {
        $this->assertRecurringSchedule('every monday at 11', '2018-04-02 11:00:00');

        $this->assertRecurringSchedule('every saturday at 11', '2018-03-31 11:00:00');
    }

    /** @test */
    function it_determines_the_next_occurrence_for_monthly_recurring_schedules()
    {
        $this->assertRecurringSchedule('monthly', '2018-04-01 08:00:00');

        $this->assertRecurringSchedule('monthly on the 25th', '2018-04-25 08:00:00');

        $this->assertRecurringSchedule('every third saturday of the month at 7:00', '2018-04-21 07:00:00');

        Carbon::setTestNow('2018-05-19 14:00:00');
        $this->assertRecurringSchedule('every third saturday of the month', '2018-06-16 08:00:00');
    }

    /** @test */
    function it_determines_the_next_occurrence_for_yearly_recurring_schedules()
    {
        $this->assertRecurringSchedule('yearly', '2019-01-01 08:00:00');

        $this->assertRecurringSchedule('yearly in may', '2018-05-01 08:00:00');
    }
}
