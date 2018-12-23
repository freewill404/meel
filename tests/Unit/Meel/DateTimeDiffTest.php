<?php

namespace Tests\Unit\Meel;

use App\Meel\DateTimeDiff;
use Tests\TestCase;

class DateTimeDiffTest extends TestCase
{
    protected $testTimes = [
        '08:00' => [
            '08:01' => 'in 1 minute',
            '08:02' => 'in a few minutes',
            '08:03' => 'in a few minutes',
            '08:04' => 'in a few minutes',
            '08:05' => 'in 5 minutes',
            '08:10' => 'in 10 minutes',
            '08:15' => 'in 15 minutes',
            '08:20' => 'in 20 minutes',
            '08:30' => 'in 30 minutes',
            '08:45' => 'in 45 minutes',

            '08:14' => 'today at 08:14',
            '08:49' => 'today at 08:49',

            '09:00' => 'in 1 hour',
            '09:01' => 'today at 09:01',
            '09:05' => 'in 1 hour and 5 minutes',
            '10:00' => 'in 2 hours',
            '10:15' => 'in 2 hours and 15 minutes',
            '16:15' => 'today at 16:15',
        ],

        '16:00' => [
            '16:06' => 'today at 16:06',
        ],
    ];

    protected $testDateTimes = [
        // Saturday
        '2018-05-12 12:00' => [
            '2018-05-13 13:00' => 'tomorrow at 13:00',
        ],

        // Saturday
        '2018-05-12 23:00' => [
            '2018-05-13 00:00' => 'in 1 hour',
            '2018-05-13 01:00' => 'in 2 hours',
            '2018-05-13 05:00' => 'tomorrow at 05:00',
            '2018-05-13 23:00' => 'tomorrow at 23:00',

            '2018-05-13 08:00' => 'tomorrow at 08:00',
            '2018-05-14 08:00' => 'next Monday at 08:00',
            '2018-05-15 08:00' => 'next Tuesday at 08:00',
            '2018-05-16 08:00' => 'next Wednesday at 08:00',
            '2018-05-17 08:00' => 'next Thursday at 08:00',
            '2018-05-18 08:00' => 'next Friday at 08:00',
            '2018-05-19 08:00' => 'next Saturday at 08:00',

            '2018-05-20 08:00' => 'on Sunday the 20th of May, at 08:00',
            '2019-05-20 08:00' => 'on Monday the 20th of May, 2019, at 08:00',
        ],

        // Sunday
        '2018-05-13 12:00' => [
            '2018-05-14 08:00' => 'tomorrow at 08:00',
            '2018-05-15 08:00' => 'next Tuesday at 08:00',
            '2018-05-16 08:00' => 'next Wednesday at 08:00',
            '2018-05-17 08:00' => 'next Thursday at 08:00',
            '2018-05-18 08:00' => 'next Friday at 08:00',
            '2018-05-19 08:00' => 'next Saturday at 08:00',
            '2018-05-20 08:00' => 'next Sunday at 08:00',
        ],

        // Monday
        '2018-05-14 12:00' => [
            '2018-05-15 08:00' => 'tomorrow at 08:00',
            '2018-05-16 08:00' => 'this Wednesday at 08:00',
            '2018-05-17 08:00' => 'this Thursday at 08:00',
            '2018-05-18 08:00' => 'this Friday at 08:00',
            '2018-05-19 08:00' => 'this Saturday at 08:00',
            '2018-05-20 08:00' => 'this Sunday at 08:00',
            '2018-05-21 08:00' => 'next Monday at 08:00',
        ],

        // Wednesday
        '2018-05-16 12:00' => [
            '2018-05-17 08:00' => 'tomorrow at 08:00',
            '2018-05-18 08:00' => 'this Friday at 08:00',
            '2018-05-19 08:00' => 'this Saturday at 08:00',
            '2018-05-20 08:00' => 'this Sunday at 08:00',
            '2018-05-21 08:00' => 'next Monday at 08:00',
            '2018-05-22 08:00' => 'next Tuesday at 08:00',
            '2018-05-23 08:00' => 'next Wednesday at 08:00',
        ],
    ];

    /** @test */
    function human_interpretation_times()
    {
        $failures = [];

        foreach ($this->testTimes as $nowTime => $values) {
            foreach ($values as $nextOccurrenceTime => $expected) {
                $dateTimeDiff = new DateTimeDiff('2018-05-15 '.$nowTime.(mt_rand(0, 1) ? ':00' : ':15'), '2018-05-15 '.$nextOccurrenceTime.':00');

                if ($dateTimeDiff->diff() !== $expected) {
                    $failures[] = '  '.$nowTime.' - '.$nextOccurrenceTime;
                    $failures[] = '  Expected: '.$expected;
                    $failures[] = '  Actual:   '.$dateTimeDiff->diff();
                    $failures[] = '';
                }
            }
        }

        $this->assertSame('', implode("\n", $failures));
    }

    /** @test */
    function human_interpretation_date_times()
    {
        $failures = [];

        $failCount = 0;

        foreach ($this->testDateTimes as $now => $values) {
            foreach ($values as $nextOccurrence => $expected) {
                $dateTimeDiff = new DateTimeDiff($now.(mt_rand(0, 1) ? ':00' : ':15'), $nextOccurrence);

                if ($dateTimeDiff->diff() !== $expected) {
                    $failCount++;

                    $failures[] = '  '.$now.' - '.$nextOccurrence;
                    $failures[] = '  Expected: '.$expected;
                    $failures[] = '  Actual:   '.$dateTimeDiff->diff();
                    $failures[] = '';
                }
            }
        }

        if ($failCount) {
            $failures[] = '';
            $failures[] = 'Errors: '.$failCount;
        }

        $this->assertSame('', implode("\n", $failures));
    }
}
