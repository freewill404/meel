<?php

namespace Tests\Unit\Meel\Schedules\WhenFormats\Recurring;

use App\Meel\Schedules\WhenFormats\Recurring\Daily;

class DailyTest extends RecurringWhenFormatTestCase
{
    protected $whenFormat = Daily::class;

    protected $shouldMatch = [
        'daily',
        'daily at 18:00',
        'daily bla bla bla 18',
        'every 2 days',
    ];

    protected $shouldNotMatch = [
        'every 0 days',
    ];

    /** @test */
    function it_can_get_the_next_date_on_the_same_day()
    {
        [$beforeNow, $exactlyNow, $afterNow] = $this->getTimeStrings();

        $this->assertNextDate('2018-03-29', 'daily', $beforeNow);

        $this->assertNextDate('2018-03-29', 'daily', $exactlyNow);

        $this->assertNextDate('2018-03-28', 'daily', $afterNow);
    }

    /** @test */
    function it_can_be_daily_with_an_interval()
    {
        $this->setTestNowDate('2018-03-15');

        $this->assertNextDate('2018-03-17', 'every 2 days');

        $this->setTestNowDate('2018-03-17');

        $this->assertNextDate('2018-03-19', 'every 2 days');
    }
}
