<?php

namespace Tests\Unit\Meel\When\Formats\Recurring;

use App\Meel\When\Formats\Recurring\Daily;

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

    protected $testValuesExcludingToday = [
        '2018-03-28' => [
            'daily' => ['2018-03-29', '2018-03-30', '2018-03-31', '2018-04-01', '2018-04-02'],
            'every 2 days' => ['2018-03-30', '2018-04-01', '2018-04-03', '2018-04-05'],
            'every 3 days' => ['2018-03-31', '2018-04-03', '2018-04-06', '2018-04-09'],
        ],
    ];

    protected $testValuesIncludingToday = [
        '2018-03-26' => [
            'daily' => ['2018-03-26', '2018-03-26'],
            'every 2 days' => ['2018-03-28', '2018-03-30', '2018-04-01'],
            'every 3 days' => ['2018-03-29', '2018-04-01', '2018-04-04'],
        ],
    ];

    protected $testIntervalDescriptions = [
        'daily' => 'daily',
        'every 2 days' => 'every 2 days',
        'every 3 days' => 'every 3 days',
    ];
}
