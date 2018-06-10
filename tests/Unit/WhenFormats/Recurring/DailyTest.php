<?php

namespace Tests\Unit\WhenFormats\Recurring;

use App\Meel\WhenFormats\Recurring\Daily;

class DailyTest extends RecurringWhenFormatTestCase
{
    protected $whenFormat = Daily::class;

    /** @test */
    function it_matches()
    {
        $this->assertWhenFormatMatches('daily');

        $this->assertWhenFormatMatches('daily at 18:00');

        $this->assertWhenFormatMatches('daily bla bla bla 18');
    }

    /** @test */
    function it_can_get_the_next_date_on_the_same_day()
    {
        [$beforeNow, $exactlyNow, $afterNow] = $this->getTimeStrings();

        $this->assertNextDate('2018-03-29', 'daily', $beforeNow);

        $this->assertNextDate('2018-03-29', 'daily', $exactlyNow);

        $this->assertNextDate('2018-03-28', 'daily', $afterNow);
    }
}
