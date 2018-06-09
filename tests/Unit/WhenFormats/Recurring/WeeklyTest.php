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
        // a wednesday
        Carbon::setTestNow('2018-03-14 12:00:15');

        [$beforeNow, $exactlyNow, $afterNow] = $this->getTimeStrings();


        $this->assertNextDate('2018-03-21', 'weekly bla bla bla wednesday', $beforeNow);

        $this->assertNextDate('2018-03-21', 'weekly on wednesday', $exactlyNow);

        $this->assertNextDate('2018-03-14', 'weekly on wednesday', $afterNow);
    }

    /** @test */
    function it_uses_a_default_day_if_no_day_is_specified()
    {
        // a wednesday
        Carbon::setTestNow('2018-03-14 12:00:15');

        // default is Monday
        $this->assertNextDate('2018-03-19', 'weekly');
    }
}
