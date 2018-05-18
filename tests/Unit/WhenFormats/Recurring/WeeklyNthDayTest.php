<?php

namespace Tests\Unit\WhenFormats\Recurring;

use App\Meel\DateTime\TimeString;
use App\Meel\WhenString;
use App\Meel\WhenFormats\Recurring\WeeklyNthDay;
use Carbon\Carbon;
use Tests\TestCase;

class WeeklyNthDayTest extends TestCase
{
    private function assertWhenFormatMatches($string)
    {
        $preparedString = WhenString::prepare($string);

        $this->assertTrue(
            WeeklyNthDay::matches($preparedString),
            'WeeklyNthDay did not match string: "'.$preparedString.'"'
        );
    }

    private function assertWhenFormatDoesNotMatch($string)
    {
        $preparedString = WhenString::prepare($string);

        $this->assertFalse(
            WeeklyNthDay::matches($preparedString),
            'WeeklyNthDay matches string: "'.$preparedString.'"'
        );
    }

    private function assertNextDate($expected, $string, $setTime = null, $timezone = null)
    {
        $preparedString = WhenString::prepare($string);

        $format = new WeeklyNthDay($preparedString);

        $setTime = new TimeString($setTime ?? now($timezone));

        $this->assertSame(
            $expected,
            $actual = (string) $format->getNextDate($setTime, $timezone),
            "WeeklyNthDay wrong next date, expected: '{$expected}', actual: '{$actual}'"
        );
    }

    /** @test */
    function it_matches()
    {
        $this->assertWhenFormatMatches('every 3rd saturday of the month');

        $this->assertWhenFormatMatches('every 4th saturday of the month');

        $this->assertWhenFormatMatches('the last monday of the month');
    }

    /** @test */
    function it_does_not_match()
    {
        $this->assertWhenFormatDoesNotMatch('every 5th saturday of the month');

        $this->assertWhenFormatDoesNotMatch('every fifth saturday of the month');
    }

    /** @test */
    function it_can_get_the_last_day()
    {
        $beforeNow  = new TimeString('11:00:00');
        $exactlyNow = new TimeString('12:00:00');
        $afterNow   = new TimeString('13:00:00');

        // The last wednesday
        Carbon::setTestNow('2018-03-28 12:00:00');

        $this->assertNextDate('2018-03-28', 'every last wednesday of the month', $beforeNow);

        $this->assertNextDate('2018-04-25', 'every last wednesday of the month', $exactlyNow);

        $this->assertNextDate('2018-04-25', 'every last wednesday of the month', $afterNow);

        // few days before the last wednesday
        Carbon::setTestNow('2018-03-26 12:00:00');

        $this->assertNextDate('2018-03-28', 'every last wednesday of the month', $afterNow);

        // few days after the last wednesday
        Carbon::setTestNow('2018-03-30 12:00:00');

        $this->assertNextDate('2018-04-25', 'every last wednesday of the month', $exactlyNow);
    }

    /** @test */
    function it_can_get_the_nth_day()
    {
        $beforeNow  = new TimeString('11:00:00');
        $exactlyNow = new TimeString('12:00:00');
        $afterNow   = new TimeString('13:00:00');

        // The second wednesday
        Carbon::setTestNow('2018-03-14 12:00:00');

        $this->assertNextDate('2018-04-04', 'the first wednesday of the month');

        $this->assertNextDate('2018-03-14', 'the second wednesday of the month', $beforeNow);

        $this->assertNextDate('2018-04-11', 'the second wednesday of the month', $exactlyNow);

        $this->assertNextDate('2018-04-11', 'the second wednesday of the month', $afterNow);

        $this->assertNextDate('2018-03-21', 'every third wednesday of the month');

        $this->assertNextDate('2018-03-28', 'every fourth wednesday of the month');
    }
}
