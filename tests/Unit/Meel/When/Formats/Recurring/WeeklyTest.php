<?php

namespace Tests\Unit\Meel\When\Formats\Recurring;

use App\Meel\When\Formats\Recurring\Weekly;

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
        'every -1 weeks',
    ];

    protected $testValuesExcludingToday = [
        '2018-03-14' => [
            'weekly' => ['2018-03-19', '2018-03-26', '2018-04-02', '2018-04-09', '2018-04-16'],
            'weekly bla bla wednesday' => ['2018-03-21', '2018-03-28', '2018-04-04', '2018-04-11', '2018-04-18'],
            'every 2 weeks on thursday' => ['2018-03-22', '2018-04-05', '2018-04-19'],
            'every 2 weeks' => ['2018-03-26', '2018-04-09', '2018-04-23', '2018-05-07'],
            'every 2 weeks on wednesday' => ['2018-03-28', '2018-04-11', '2018-04-25', '2018-05-09'],
            'every 2 weeks on tuesday' => ['2018-03-27', '2018-04-10', '2018-04-24', '2018-05-08'],
            'every 4 weeks' => ['2018-04-09', '2018-05-07', '2018-06-04'],
        ],

        '2018-03-28' => [
            'every 2 weeks on wednesday' => ['2018-04-11', '2018-04-25', '2018-05-09'],
            'every 2 weeks on tuesday' => ['2018-04-10', '2018-04-24', '2018-05-08'],
            'every 4 weeks' => ['2018-04-23', '2018-05-21', '2018-06-18'],
        ],
    ];

    protected $testValuesIncludingToday = [
        // Wednesday
        '2018-03-14' => [
            'weekly on wednesday' => ['2018-03-14', '2018-03-14'],

            'every 3 weeks on wednesday' => ['2018-04-04', '2018-04-25'],
        ],
    ];

    protected $testIntervalDescriptions = [
        'weekly' => 'weekly',
        'biweekly' => 'every 2 weeks',
        'every 3 weeks' => 'every 3 weeks',
    ];
}
