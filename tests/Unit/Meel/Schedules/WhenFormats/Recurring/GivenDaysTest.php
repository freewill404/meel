<?php

namespace Tests\Unit\Meel\Schedules\WhenFormats\Recurring;

use App\Meel\Schedules\WhenFormats\Recurring\GivenDays;
use App\Meel\Schedules\WhenString;

class GivenDaysTest extends RecurringWhenFormatTestCase
{
    protected $whenFormat = GivenDays::class;

    protected $shouldMatch = [
        'every monday',
        'every monday, tues and thursday'
    ];

    /** @test */
    function it_can_get_the_next_date_on_the_same_day()
    {
        [$beforeNow, $exactlyNow, $afterNow] = $this->getTimeStrings();

        $this->assertNextDate('2018-03-29', 'every wednesday and thursday', $beforeNow);

        $this->assertNextDate('2018-03-29', 'every wednesday and thursday', $exactlyNow);

        $this->assertNextDate('2018-03-28', 'every wednesday and thursday', $afterNow);
    }

    /** @test */
    function it_interprets_given_days()
    {
        $this->assertNextDate('2018-03-29', 'every thursday, friday and monday');

        $this->setTestNowDate('2018-03-29');

        $this->assertNextDate('2018-03-30', 'every thursday, friday and monday');

        $this->setTestNowDate('2018-03-30');

        $this->assertNextDate('2018-04-02', 'every thursday, friday and monday');

        $this->setTestNowDate('2018-04-02');

        $this->assertNextDate('2018-04-05', 'every thursday, friday and monday');
    }

    /** @test */
    function it_can_get_the_next_day_with_one_given_day()
    {
        $this->assertNextDate('2018-03-29', 'every thursday');

        $this->setTestNowDate('2018-03-29');

        $this->assertNextDate('2018-04-05', 'every thursday');

        $this->setTestNowDate('2018-04-05');

        $this->assertNextDate('2018-04-12', 'every thursday');
    }

    /** @test */
    function it_has_all_the_given_days()
    {
        $this->assertGivenDays('every monday', ['Monday']);

        $this->assertGivenDays('every monday and monday', ['Monday']);

        $this->assertGivenDays('every mon,wed,thurs', ['Monday', 'Wednesday', 'Thursday']);

        $this->assertGivenDays('every monday, tuesday, wednesday, thursday, friday, saturday, sunday', [
            'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday',
        ]);
    }

    /** @test */
    function it_has_the_correct_interval_description()
    {
        $this->assertIntervalDescription('on 3 given days', 'every thursday, friday and monday');

        $this->assertIntervalDescription('every Wednesday', 'every wednesday');

        $this->assertIntervalDescription('every Friday', 'every friday and friday');
    }

    private function assertGivenDays($string, array $expectedDays)
    {
        $preparedString = WhenString::prepare($string);

        $class = new class($preparedString) extends GivenDays {
            public function getDays()
            {
                return $this->days;
            }
        };

        $this->assertSame($expectedDays, $class->getDays());
    }
}
