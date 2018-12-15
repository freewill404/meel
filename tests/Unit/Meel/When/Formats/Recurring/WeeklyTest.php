<?php

namespace Tests\Unit\Meel\When\Formats\Recurring;

use App\Meel\When\Formats\Recurring\Weekly;
use Carbon\Carbon;

class WeeklyTest extends RecurringWhenFormatTestCase
{
    protected $whenFormat = Weekly::class;

    protected $shouldMatch = [
        'weekly',
        'every week on wednesday',
        'weekly bla bla bla wednesday',
        'every 2 weeks on thursday',
    ];

    protected $shouldNotMatch = [
        'every 0 weeks',
    ];

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
    function it_can_have_a_week_interval()
    {
        // a wednesday
        Carbon::setTestNow('2018-03-14 12:00:15');

        [$beforeNow, $exactlyNow, $afterNow] = $this->getTimeStrings();

        // Default day is monday
        $this->assertNextDate('2018-03-26', 'every 2 weeks', $beforeNow);

        // On a wednesday (exactly 14 days later)
        $this->assertNextDate('2018-03-28', 'every 2 weeks on wednesday', $exactlyNow);

        $this->assertNextDate('2018-03-27', 'every 2 weeks on tuesday', $afterNow);

        $this->assertNextDate('2018-04-09', 'every 4 weeks', $afterNow);

        // a wednesday
        Carbon::setTestNow('2018-03-28 12:00:15');

        $this->assertNextDate('2018-04-11', 'every 2 weeks on wednesday', $exactlyNow);

        $this->assertNextDate('2018-04-10', 'every 2 weeks on tuesday', $afterNow);

        Carbon::setTestNow('2018-04-09 12:00:15');

        $this->assertNextDate('2018-05-07', 'every 4 weeks', $afterNow);
    }

    /** @test */
    function the_current_week_counts_as_a_week()
    {
        // a wednesday
        Carbon::setTestNow('2018-03-14 12:00:15');

        $this->assertNextDate('2018-03-22', 'every 2 weeks on thursday');

        Carbon::setTestNow('2018-03-22 12:00:15');

        $this->assertNextDate('2018-04-05', 'every 2 weeks on thursday');
    }

    /** @test */
    function the_current_week_counts_as_a_week_regardless_of_the_current_time()
    {
        // a wednesday
        Carbon::setTestNow('2018-03-14 12:00:15');

        [$beforeNow, $exactlyNow, $afterNow] = $this->getTimeStrings();

        // The time is after now, so we could still trigger this schedule today,
        // but weekly schedules should ignore the time and only look at the day.
        $this->assertNextDate('2018-04-04', 'every 3 weeks on wednesday', $afterNow);

        Carbon::setTestNow('2018-04-04 12:00:15');

        $this->assertNextDate('2018-04-25', 'every 3 weeks on wednesday', $afterNow);
    }

    /** @test */
    function it_uses_a_default_day_if_no_day_is_specified()
    {
        // a wednesday
        Carbon::setTestNow('2018-03-14 12:00:15');

        // default is Monday
        $this->assertNextDate('2018-03-19', 'weekly');
    }

    /** @test */
    function it_has_the_correct_interval_description()
    {
        $this->assertIntervalDescription('weekly', 'weekly');

        $this->assertIntervalDescription('every 2 weeks', 'biweekly');
    }
}
