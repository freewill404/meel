<?php

namespace Tests\Unit\WhenFormats\Recurring;

use App\Meel\WhenFormats\Recurring\Weekly;
use Carbon\Carbon;

class WeeklyTest extends RecurringWhenFormatTestCase
{
    protected $whenFormat = Weekly::class;

    /** @test */
    function it_matches()
    {
        $this->assertWhenFormatMatches('weekly');

        $this->assertWhenFormatMatches('weekly on wednesday');

        $this->assertWhenFormatMatches('weekly bla bla bla wednesday');
    }

    /** @test */
    function it_can_get_the_next_date_on_the_same_day()
    {
        [$beforeNow, $exactlyNow, $afterNow] = $this->getTimeStrings();

        // a wednesday
        Carbon::setTestNow('2018-03-14 12:00:00');

        $this->assertNextDate('2018-03-14', 'weekly bla bla bla wednesday', $beforeNow);

        $this->assertNextDate('2018-03-21', 'weekly on wednesday', $exactlyNow);

        $this->assertNextDate('2018-03-21', 'weekly on wednesday', $afterNow);
    }

    /** @test */
    function it_uses_a_default_day_if_no_day_is_specified()
    {
        // a wednesday
        Carbon::setTestNow('2018-03-14 12:00:00');

        // default is Monday
        $this->assertNextDate('2018-03-19', 'weekly');
    }
}
