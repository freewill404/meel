<?php

namespace Tests\Unit\Meel\When;

use App\Meel\When\ScheduleFormat;
use App\Meel\When\WhenString;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class ScheduleFormatTest extends TestCase
{
    private $singleOccurrenceSchedules = [
        '2018-03-28 12:00' => [
            'in 1 minute' => '2018-03-28 12:01',
            'now' => '2018-03-28 12:00',
            'at 18' => '2018-03-28 18:00',
            'tomorrow at 17' => '2018-03-29 17:00',
            'tomorrow at 17:00' => '2018-03-29 17:00',
            'next wednesday' => '2018-04-04 08:00',
            'next sat' => '2018-03-31 08:00',
            'this sat at 22:00' => '2018-03-31 22:00',
            'saturday at 22:00' => '2018-03-31 22:00',
            'sat at 22:00' => '2018-03-31 22:00',
            'sat' => '2018-03-31 08:00',
            'sept 1' => '2018-09-01 08:00',
            '1st sept 2020' => '2020-09-01 08:00',
            '2020 1 sept' => '2020-09-01 08:00',
            '1-may-2020' => '2020-05-01 08:00',
            '2020-05' => '2020-05-01 08:00',
            '05-2020' => '2020-05-01 08:00',
            '05 2020' => '2020-05-01 08:00',
            '2020-05-01' => '2020-05-01 08:00',
            '2020 05 01' => '2020-05-01 08:00',
            '7-16' => '2018-07-16 08:00',
            '01-02' => '2019-02-01 08:00', // short date is in the past
            '1-9' => '2018-09-01 08:00',
            '12-06' => '2018-06-12 08:00',
            '06-12' => '2018-12-06 08:00',
            '06-13' => '2018-06-13 08:00',
            '18-07 at 7' => '2018-07-18 07:00',

            'in 5 mins' => '2018-03-28 12:05',
            'in 1 min please' => '2018-03-28 12:01',
        ],
    ];

    private $recurringSchedules = [
        '2018-03-28 12:00' => [
            'every 2 weeks on sat at 11' => ['2018-04-07 11:00', '2018-04-21 11:00'],
            'every saturday at 11' => ['2018-03-31 11:00', '2018-04-07 11:00'],
            'every month' => ['2018-04-01 08:00', '2018-05-01 08:00'],
            'every 2 months on the 25th' => ['2018-05-25 08:00', '2018-07-25 08:00'],
            'yearly' => ['2019-01-01 08:00', '2020-01-01 08:00'],
            'yearly in may' => ['2018-05-01 08:00', '2019-05-01 08:00'],
            'daily' => ['2018-03-29 08:00', '2018-03-30 08:00'],
            'daily at 16:20' => ['2018-03-28 16:20', '2018-03-29 16:20', '2018-03-30 16:20'],

            'every 3rd sat of the month at 7:00' => ['2018-04-21 07:00'],

            'every monday and tuesday at 11' => ['2018-04-02 11:00', '2018-04-03 11:00', '2018-04-09 11:00'],
        ],

        '2018-05-19 14:00' => [
            'every third saturday of the month' => ['2018-06-16 08:00', '2018-07-21 08:00', '2018-08-18 08:00'],
        ],

    ];

    private $noMatch = [
        '2018-03-28 12:00' => [
            'next',
            'next durrday',
            'at 7', // "at 7" is in the past
        ],
    ];

    /** @test */
    function it_determines_the_next_occurrence_for_single_occurrence_schedules()
    {
        foreach ($this->singleOccurrenceSchedules as $now => $values) {
            foreach ($values as $writtenInput => $expectedNextOccurrence) {
                $scheduleFormat = new ScheduleFormat("$now:15", $writtenInput);

                $this->assertTrue(
                    $scheduleFormat->usable(),
                    "'$writtenInput' was not interpreted as a usable interpretation"
                );

                $this->assertFalse(
                    $scheduleFormat->recurring(),
                    "'$writtenInput' was interpreted as recurring, it should not be"
                );

                $this->assertSame(
                    $expectedNextOccurrence.':00',
                    $actual = (string) $scheduleFormat->nextOccurrence(),
                    implode("\n", [
                        "",
                        "Wrong interpretation",
                        "",
                        "Input:    {$writtenInput}",
                        "Prepared: ".(new WhenString)->prepare($writtenInput),
                        "Expected: {$expectedNextOccurrence} ".Carbon::parse($expectedNextOccurrence)->format('l'),
                        "Actual:   {$actual} ".Carbon::parse($actual)->format('l'),
                        "",
                        "Current now: $now ".Carbon::parse($now)->format('l'),
                        "",
                    ])
                );
            }
        }
    }

    /** @test */
    function it_determines_the_next_occurrence_for_recurring_schedules()
    {
        foreach ($this->recurringSchedules as $startNow => $values) {
            foreach ($values as $writtenInput => $expectedNextOccurrences) {
                $now = $startNow.':15';

                foreach ($expectedNextOccurrences as $expectedNextOccurrence) {
                    $scheduleFormat = new ScheduleFormat($now, $writtenInput);

                    $this->assertTrue(
                        $scheduleFormat->usable(),
                        "''$writtenInput'' was not interpreted as a usable interpretation"
                    );

                    $this->assertTrue(
                        $scheduleFormat->recurring(),
                        "'$writtenInput' was not interpreted as recurring, it should be"
                    );

                    $this->assertSame(
                        $expectedNextOccurrence.':00',
                        $actual = (string) $scheduleFormat->nextOccurrence(),
                        "'{$writtenInput}' next occurrence was '{$actual}', should be '{$expectedNextOccurrence}'"
                    );

                    $now = $actual;
                }
            }
        }
    }

    /** @test */
    function it_does_not_match_things()
    {
        foreach ($this->noMatch as $now => $values) {
            foreach ($values as $writtenInput) {
                $scheduleFormat = new ScheduleFormat("$now:15", $writtenInput);

                $this->assertFalse(
                    $scheduleFormat->usable(),
                    "'$writtenInput' should not be a usable interpretation"
                );
            }
        }
    }
}
