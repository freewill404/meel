<?php

namespace Tests\Unit\Meel\When\Formats;

use App\Meel\When\Formats\RelativeToNowInterpretation;
use Carbon\Carbon;
use Tests\TestCase;

class RelativeToNowInterpretationTest extends TestCase
{
    private $testValues = [
        '2018-03-28 12:00' => [
            'now' => '2018-03-28 12:00:00',
            'right now' => '2018-03-28 12:00:00',

            'next year' => '2019-03-28 08:00:00',
            'in 1 year' => '2019-03-28 08:00:00',
            'in 3 years' => '2021-03-28 08:00:00',

            'next month' => '2018-04-28 08:00:00',
            'in 1 month' => '2018-04-28 08:00:00',
            '3 months from now' => '2018-06-28 08:00:00',

            'next week' => '2018-04-04 08:00:00',
            '3 weeks from now' => '2018-04-18 08:00:00',

            'tomorrow' => '2018-03-29 08:00:00',
            'tomorrow at 17:00' => '2018-03-29 08:00:00',
            'in 1 day' => '2018-03-29 08:00:00',
            '3 days from now' => '2018-03-31 08:00:00',

            '3 hours from now' => '2018-03-28 15:00:00',
            '3 minutes from now' => '2018-03-28 12:03:00',
            '2 hours and 15 minutes from now' => '2018-03-28 14:15:00',

            // It uses a default time if no time is specified.
            'in 1 week' => '2018-04-04 08:00:00',
            'in 1 hour' => '2018-03-28 13:00:00',

            'next saturday' => '2018-03-31 08:00:00',
            'this tues' => '2018-04-03 08:00:00',
        ],
    ];

    private $notRelativeToNow = [
        '2018-03-28 12:00' => [
            '1 hour',
            '24th of may 2018',
        ],
    ];

    /** @test */
    function relative_to_now()
    {
        foreach ($this->testValues as $now => $values) {
            foreach ($values as $writtenInput => $expected) {
                $relativeNow = new RelativeToNowInterpretation($now, $writtenInput);

                if (! $relativeNow->isRelativeToNow()) {
                    $this->fail("RelativeToNowInterpretation interpreted '{$writtenInput}' as not being relative to now, should be '{$expected}'");
                }

                $this->assertSame(
                    $expected,
                    $actual = (string) $relativeNow->getDateTime(),

                    "\nWrong RelativeToNowInterpretation\n Input:    '{$writtenInput}'\n Expected: '{$expected}' ".
                    Carbon::parse($expected)->format('l').
                    "\n Actual:   '{$actual}' ".
                    Carbon::parse($actual)->format('l').
                    "\n\n Current now: $now\n"
                );
            }
        }
    }

    /** @test */
    function not_relative_to_now()
    {
        foreach ($this->notRelativeToNow as $now => $values) {
            foreach ($values as $writtenInput) {
                $relativeNow = new RelativeToNowInterpretation($now, $writtenInput);

                $this->assertFalse(
                    $relativeNow->isRelativeToNow(),
                    "RelativeToNowInterpretation interpreted '{$writtenInput}' to be relative to now"
                );
            }
        }
    }
}
