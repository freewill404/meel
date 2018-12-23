<?php

namespace Tests\Unit\Meel\When\Formats\Recurring;

use App\Meel\When\Formats\Recurring\Monthly;

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

    protected $testValuesExcludingToday = [
        '2018-03-28' => [
            'monthly' => ['2018-04-01', '2018-05-01', '2018-06-01', '2018-07-01'],
            'every 2 months' => ['2018-05-01', '2018-07-01', '2018-09-01', '2018-11-01'],

            // It uses the closest possible date if the month is short.
            'monthly on the 31st' => ['2018-03-31', '2018-04-30', '2018-05-31', '2018-06-30'],

            'bimonthly on the 15th' => ['2018-05-15', '2018-07-15', '2018-09-15', '2018-11-15'],

            'every 3 months' => ['2018-06-01', '2018-09-01', '2018-12-01', '2019-03-01'],
        ],
    ];

    protected $testValuesIncludingToday = [
        '2018-03-14' => [
            'monthly on the 14th' => ['2018-03-14', '2018-03-14'],
        ],
    ];

    protected $testIntervalDescriptions = [
        'every month' => 'monthly',
        'bimonthly' => 'every 2 months',
        'every 3 months' => 'every 3 months',
    ];
}
