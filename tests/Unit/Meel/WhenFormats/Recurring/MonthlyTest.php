<?php

namespace Tests\Unit\Meel\WhenFormats\Recurring;

use App\Meel\WhenFormats\Recurring\Monthly;
use Carbon\Carbon;

class MonthlyTest extends RecurringWhenFormatTestCase
{
    protected $whenFormat = Monthly::class;

    protected $shouldMatch = [
        'every month',
        'every 2 months',
        'monthly on the 28th',
    ];

    protected $shouldNotMatch = [
        'every 0 months'
    ];

    /** @test */
    function it_uses_a_default_date_if_no_date_is_specified()
    {
        $this->assertNextDate('2018-04-01', 'monthly');
    }

    /** @test */
    function it_can_get_the_next_date_on_short_months()
    {
        // May has 31 days
        Carbon::setTestNow('2018-05-01 12:00:15');

        $this->assertNextDate('2018-05-31', 'monthly on the 31st');

        // June has 30 days
        Carbon::setTestNow('2018-06-01 12:00:15');

        // It uses the closest possible date if the month is short.
        $this->assertNextDate('2018-06-30', 'monthly on the 31st');
    }

    /** @test */
    function it_can_get_the_next_date_on_the_same_day()
    {
        Carbon::setTestNow('2018-03-14 12:00:15');

        [$beforeNow, $exactlyNow, $afterNow] = $this->getTimeStrings();

        $this->assertNextDate('2018-04-14', 'monthly on the 14th', $beforeNow);

        $this->assertNextDate('2018-04-14', 'monthly on the 14th', $exactlyNow);

        $this->assertNextDate('2018-03-14', 'monthly on the 14th', $afterNow);
    }

    /** @test */
    function it_can_have_a_month_interval()
    {
        $this->assertNextDate('2018-03-31', 'every 1 months on the 31st');
        $this->assertNextDate('2018-05-31', 'bimonthly on the 31st');
        $this->assertNextDate('2018-06-30', 'every 3 months on the 31st');

        Carbon::setTestNow('2018-05-31 12:00:15');

        $this->assertNextDate('2018-07-31', 'bimonthly on the 31st');
    }

    /** @test */
    function it_has_the_correct_interval_description()
    {
        $this->assertIntervalDescription('monthly', 'every month');
        $this->assertIntervalDescription('every 2 months', 'bimonthly');
    }
}
