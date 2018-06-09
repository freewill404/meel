<?php

namespace Tests\Unit\WhenFormats\Recurring;

use App\Meel\WhenFormats\Recurring\MonthlyNthDay;
use Carbon\Carbon;

class MonthlyNthDayTest extends RecurringWhenFormatTestCase
{
    protected $whenFormat = MonthlyNthDay::class;

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
        $this->assertWhenFormatDoesNotMatch('every 5th sat of the month');

        $this->assertWhenFormatDoesNotMatch('every fifth saturday of the month');
    }

    /** @test */
    function it_can_get_the_last_day()
    {
        [$beforeNow, $exactlyNow, $afterNow] = $this->getTimeStrings();

        // few days before the last wednesday
        Carbon::setTestNow('2018-03-26 12:00:15');

        $this->assertNextDate('2018-03-28', 'every last wednesday of the month', $afterNow);

        // few days after the last wednesday
        Carbon::setTestNow('2018-03-30 12:00:15');

        $this->assertNextDate('2018-04-25', 'every last wednesday of the month', $exactlyNow);
    }

    /** @test */
    function it_can_get_the_last_day_if_today_is_the_last_day()
    {
        [$beforeNow, $exactlyNow, $afterNow] = $this->getTimeStrings();

        // The last wednesday
        Carbon::setTestNow('2018-03-28 12:00:15');

        $this->assertNextDate('2018-04-25', 'every last wednesday of the month', $beforeNow);

        $this->assertNextDate('2018-04-25', 'every last wed of the month', $exactlyNow);

        $this->assertNextDate('2018-03-28', 'every last wednesday of the month', $afterNow);
    }

    /** @test */
    function it_can_get_the_nth_day()
    {
        // The second wednesday
        Carbon::setTestNow('2018-03-14 12:00:15');

        $this->assertNextDate('2018-04-04', 'the first wednesday of the month');

        $this->assertNextDate('2018-03-21', 'every third wed of the month');

        $this->assertNextDate('2018-03-28', 'every fourth wednesday of the month');
    }

    /** @test */
    function it_can_get_the_nth_day_if_it_is_today()
    {
        // The second wednesday
        Carbon::setTestNow('2018-03-14 12:00:15');

        [$beforeNow, $exactlyNow, $afterNow] = $this->getTimeStrings();

        $this->assertNextDate('2018-04-11', 'the second wednesday of the month', $beforeNow);

        $this->assertNextDate('2018-04-11', 'the second wed of the month', $exactlyNow);

        $this->assertNextDate('2018-03-14', 'the second wednesday of the month', $afterNow);
    }
}
