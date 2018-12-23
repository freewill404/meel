<?php

namespace Tests\Unit\Meel\When\Formats\Recurring;

use App\Meel\When\Formats\Recurring\MonthlyNthDay;

class MonthlyNthDayTest extends RecurringWhenFormatTestCase
{
    protected $whenFormat = MonthlyNthDay::class;

    protected $shouldMatch = [
        'every last wed of the month',
        'every 3rd saturday of the month',
        'every 4th saturday of the month',
        'the last monday of the month',
    ];

    protected $shouldNotMatch = [
        'every 5th sat of the month',
        'every fifth saturday of the month',
    ];

    protected $testValuesExcludingToday = [
        '2018-03-26' => [
            'the first wednesday of the month' => ['2018-04-04', '2018-05-02', '2018-06-06'],
            'the second wednesday of the month' => ['2018-04-11', '2018-05-09', '2018-06-13'],
            'every third wed of the month' => ['2018-04-18', '2018-05-16', '2018-06-20'],
            'every fourth wednesday of the month' => ['2018-03-28', '2018-04-25', '2018-05-23', '2018-06-27'],
            'every last wednesday of the month' => ['2018-03-28', '2018-04-25', '2018-05-30', '2018-06-27'],
        ],
    ];

    protected $testValuesIncludingToday = [
        '2018-03-26' => [
            'every last wednesday of the month' => ['2018-03-28', '2018-03-28'],
            'the second wednesday of the month' => ['2018-04-11', '2018-04-11'],
        ],

        '2018-03-28' => [
            'every last wednesday of the month' => ['2018-03-28', '2018-03-28'],
        ],

        '2018-03-15' => [
            'the second wednesday of the month' => ['2018-04-11', '2018-04-11'],
        ],
    ];

    protected $testIntervalDescriptions = [
        'the first wednesday of the month' => 'monthly',
        'the second wednesday of the month' => 'monthly',
        'every third wed of the month' => 'monthly',
        'every fourth wednesday of the month' => 'monthly',
        'every last wednesday of the month' => 'monthly',
    ];
}
