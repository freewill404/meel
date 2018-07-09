<?php

namespace Tests\Unit\Meel;

use App\Meel\EmailScheduleFormat;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class EmailScheduleFormatTest extends TestCase
{
    /** @test */
    function it_determines_the_next_occurrence_for_non_recurring_schedules()
    {
        $this->assertSingleOccurrenceSchedule('in 1 minute', '2018-03-28 12:01:00');

        $this->assertSingleOccurrenceSchedule('now', '2018-03-28 12:00:00');

        $this->assertSingleOccurrenceSchedule('at 18', '2018-03-28 18:00:00');

        $this->assertSingleOccurrenceSchedule('tomorrow at 17', '2018-03-29 17:00:00');

        $this->assertSingleOccurrenceSchedule('next wednesday', '2018-04-04 08:00:00');
        $this->assertSingleOccurrenceSchedule('next sat',       '2018-03-31 08:00:00');

        $this->assertSingleOccurrenceSchedule('this saturday at 22:00', '2018-03-31 22:00:00');
        $this->assertSingleOccurrenceSchedule('saturday at 22:00',      '2018-03-31 22:00:00');
        $this->assertSingleOccurrenceSchedule('sat at 22:00',           '2018-03-31 22:00:00');
        $this->assertSingleOccurrenceSchedule('sat',                    '2018-03-31 08:00:00');
    }

    /** @test */
    function it_determines_the_next_occurrence_for_weekly_recurring_schedules()
    {
        $this->assertRecurringSchedule('every week on saturday at 11', '2018-03-31 11:00:00');

        $this->assertRecurringSchedule('every two weeks on saturday at 11', '2018-04-07 11:00:00');
    }

    /** @test */
    function it_determines_the_next_occurrence_for_given_days_recurring_schedules()
    {
        $this->assertRecurringSchedule('every saturday at 11', '2018-03-31 11:00:00');

        $this->assertRecurringSchedule('every monday and tuesday at 11', '2018-04-02 11:00:00');
        $this->setTestNowDate('2018-04-02');
        $this->assertRecurringSchedule('every monday and tuesday at 11', '2018-04-03 11:00:00');
        $this->setTestNowDate('2018-04-03');
        $this->assertRecurringSchedule('every monday and tuesday at 11', '2018-04-09 11:00:00');
    }

    /** @test */
    function it_determines_the_next_occurrence_for_monthly_recurring_schedules()
    {
        $this->assertRecurringSchedule('every month', '2018-04-01 08:00:00');

        $this->assertRecurringSchedule('every 2 months on the 25th', '2018-05-25 08:00:00');

        $this->assertRecurringSchedule('every third saturday of the month at 7:00', '2018-04-21 07:00:00');

        Carbon::setTestNow('2018-05-19 14:00:15');
        $this->assertRecurringSchedule('every third saturday of the month', '2018-06-16 08:00:00');
    }

    /** @test */
    function it_determines_the_next_occurrence_for_yearly_recurring_schedules()
    {
        $this->assertRecurringSchedule('yearly', '2019-01-01 08:00:00');

        $this->assertRecurringSchedule('yearly in may', '2018-05-01 08:00:00');
    }

    /** @test */
    function it_determines_the_next_occurrence_for_daily_recurring_schedules()
    {
        $this->assertRecurringSchedule('daily', '2018-03-29 08:00:00');

        $this->assertRecurringSchedule('daily at 16:20', '2018-03-28 16:20:00');
    }

    /** @test */
    function it_does_not_match_things()
    {
        $this->assertNotUsable('next');
        $this->assertNotUsable('next durrday');

        // "at 7" is in the past (now: 2018-03-28 12:00:00)
        $this->assertNotUsable('at 7');
    }

    private function assertNotUsable(string $writtenInput)
    {
        $scheduleFormat = new EmailScheduleFormat($writtenInput);

        $this->assertFalse(
            $scheduleFormat->isUsableInterpretation(),
            '"'.$writtenInput.'" should not be a usable interpretation'
        );
    }

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
            $actual = (string) $scheduleFormat->nextOccurrence(),

            "\nWrong interpretation\n Input:    '{$writtenInput}'\n Expected: '{$expectedNextOccurrence}' ".
            Carbon::parse($expectedNextOccurrence)->format('l').
            "\n Actual:   '{$actual}' ".
            Carbon::parse($actual)->format('l').
            "\n\n Current now: ".now()." ".now()->format('l')."\n"
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
            $actual = (string) $scheduleFormat->nextOccurrence(),
            "'{$writtenInput}' next occurrence was '{$actual}', should be '{$expectedNextOccurrence}'"
        );
    }
}
