<?php

namespace Tests\Unit\WhenFormats\Recurring;

use App\Meel\WhenFormats\Recurring\Monthly;
use Carbon\Carbon;

class MonthlyTest extends RecurringWhenFormatTestCase
{
    protected $whenFormat = Monthly::class;

    /** @test */
    function it_matches()
    {
        $this->assertWhenFormatMatches('monthly');

        $this->assertWhenFormatMatches('monthly on the 28th');
    }

    /** @test */
    function it_uses_a_default_date_if_no_date_is_specified()
    {
        $this->assertNextDate('2018-04-01', 'monthly');
    }

    /** @test */
    function it_can_get_the_next_date_on_short_months()
    {
        // May has 31 days
        Carbon::setTestNow('2018-05-01 12:00:00');

        $this->assertNextDate('2018-05-31', 'monthly on the 31st');

        // June has 30 days
        Carbon::setTestNow('2018-06-01 12:00:00');

        // It uses the closest possible date if the month is short.
        $this->assertNextDate('2018-06-30', 'monthly on the 31st');
    }

    /** @test */
    function it_can_get_the_next_date_on_the_same_day()
    {
        [$beforeNow, $exactlyNow, $afterNow] = $this->getTimeStrings();

        Carbon::setTestNow('2018-03-14 12:00:00');

        $this->assertNextDate('2018-04-14', 'monthly on the 14th', $beforeNow);

        $this->assertNextDate('2018-04-14', 'monthly on the 14th', $exactlyNow);

        $this->assertNextDate('2018-03-14', 'monthly on the 14th', $afterNow);
    }
}
