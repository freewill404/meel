<?php

namespace Tests\Unit\Meel\When\Formats\Recurring;

use App\Meel\When\Formats\Recurring\GivenDays;
use App\Meel\When\WhenString;

class GivenDaysTest extends RecurringWhenFormatTestCase
{
    protected $whenFormat = GivenDays::class;

    protected $shouldMatch = [
        'every monday',
        'every monday, tues and thursday'
    ];

    protected $testValuesExcludingToday = [
        '2018-03-29' => [
            'every wednesday and thursday' => ['2018-04-04', '2018-04-05', '2018-04-11', '2018-04-12', '2018-04-18', '2018-04-19'],
            'every sunday, friday and monday' => ['2018-03-30', '2018-04-01', '2018-04-02', '2018-04-06', '2018-04-08', '2018-04-09'],
            'every thursday' => ['2018-04-05', '2018-04-12', '2018-04-19', '2018-04-26'],

        ],
    ];

    protected $testValuesIncludingToday = [
        // Wednesday
        '2018-03-28' => [
            'every wednesday and thursday' => ['2018-03-28', '2018-03-28'],
            'every thurs' => ['2018-03-29', '2018-03-29'],
        ],

        // Tuesday
        '2018-03-27' => [
            'every wednesday' => ['2018-03-28', '2018-03-28'],
        ],
    ];

    protected $testIntervalDescriptions = [
        'every thursday, friday and monday' => 'on 3 given days',
        'every wednesday' => 'every Wednesday',
        'every friday and friday' => 'every Friday',
    ];

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

    private function assertGivenDays($string, array $expectedDays)
    {
        $preparedString = (new WhenString)->prepare($string);

        $class = new class($preparedString) extends GivenDays {
            public function getDays()
            {
                return $this->days;
            }
        };

        $this->assertSame($expectedDays, $class->getDays());
    }
}
