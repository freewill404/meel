<?php

namespace Tests\Unit\WhenFormats\Recurring;

use App\Meel\WhenFormats\Recurring\Yearly;
use Carbon\Carbon;

class YearlyTest extends RecurringWhenFormatTestCase
{
    protected $whenFormat = Yearly::class;

    /** @test */
    function it_matches()
    {
        $this->assertWhenFormatMatches('yearly');

        $this->assertWhenFormatMatches('yearly on the 28th of March');
    }

    /** @test */
    function it_uses_a_default_date_if_no_date_is_specified()
    {
        $this->assertNextDate('2019-01-01', 'yearly');
    }

    /** @test */
    function it_can_get_the_next_date()
    {
        Carbon::setTestNow('2018-05-01 12:00:15');

        $this->assertNextDate('2019-04-02', 'yearly on the second of April');

        $this->assertNextDate('2019-04-01', 'yearly in April');

        $this->assertNextDate('2018-08-01', 'yearly in August');

        $this->assertNextDate('2019-01-20', 'yearly on the 20th');
    }

    /** @test */
    function it_can_get_the_next_date_on_short_months()
    {
        // May has 31 days
        Carbon::setTestNow('2018-05-01 12:00:15');

        $this->assertNextDate('2018-05-31', 'yearly on the 31st of May');

        // June has 30 days
        Carbon::setTestNow('2018-06-01 12:00:15');

        // It uses the closest possible date if the month is short.
        $this->assertNextDate('2018-06-30', 'yearly on the 31st of June');
    }

    /** @test */
    function it_can_get_the_next_date_on_the_same_day()
    {
        Carbon::setTestNow('2018-03-14 12:00:15');

        [$beforeNow, $exactlyNow, $afterNow] = $this->getTimeStrings();

        $this->assertNextDate('2019-03-14', 'yearly on the 14th of March', $beforeNow);

        $this->assertNextDate('2019-03-14', 'yearly on the 14th of March', $exactlyNow);

        $this->assertNextDate('2018-03-14', 'yearly on the 14th of March', $afterNow);
    }
}
