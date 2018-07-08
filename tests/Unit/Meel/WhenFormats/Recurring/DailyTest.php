<?php

namespace Tests\Unit\Meel\WhenFormats\Recurring;

use App\Meel\WhenFormats\Recurring\Daily;

class DailyTest extends RecurringWhenFormatTestCase
{
    protected $whenFormat = Daily::class;

    protected $shouldMatch = [
        'daily',
        'daily at 18:00',
        'daily bla bla bla 18',
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
}
