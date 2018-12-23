<?php

namespace Tests\Unit\Meel\When\Formats\Recurring;

use App\Meel\When\Formats\Recurring\Yearly;

class YearlyTest extends RecurringWhenFormatTestCase
{
    protected $whenFormat = Yearly::class;

    protected $shouldMatch = [
        'yearly',
        'yearly on the 28th of March',
        'every 2 years',
    ];

    protected $shouldNotMatch = [
        'every 0 years',
        'every -1 years',
    ];

    protected $testValuesExcludingToday = [
        '2018-05-01' => [
            'yearly' => ['2019-01-01', '2020-01-01', '2021-01-01'],
            'yearly on the second of April' => ['2019-04-02', '2020-04-02', '2021-04-02'],
            'yearly in Sept' => ['2018-09-01', '2019-09-01', '2020-09-01'],
            'yearly in Aug' => ['2018-08-01', '2019-08-01', '2020-08-01'],
            'yearly on the 20th' => ['2019-01-20', '2020-01-20', '2021-01-20'],

            'yearly on the 31st of May' => ['2018-05-31', '2019-05-31', '2020-05-31'],

            // It uses the closest possible date if the month is short.
            'yearly on the 31st of June' => ['2018-06-30', '2019-06-30', '2020-06-30'],

            'every 2 years in May' => ['2020-05-01', '2022-05-01', '2024-05-01'],

            'every decade' => ['2028-01-01', '2038-01-01', '2048-01-01'],
            'every century' => ['2118-01-01', '2218-01-01', '2318-01-01'],
            'every millennium' => ['3018-01-01', '4018-01-01', '5018-01-01'],

            'yearly on the 29th of feb' => ['2019-02-28', '2020-02-29', '2021-02-28', '2022-02-28'],

            'every 2 years on the 29th of feb' => ['2020-02-29', '2022-02-28', '2024-02-29', '2026-02-28'],
        ],
    ];

    protected $testValuesIncludingToday = [
        '2018-03-14' => [
            'yearly on the 14th of March' => ['2018-03-14', '2018-03-14'],
        ],
    ];

    protected $testIntervalDescriptions = [
        'yearly' => 'yearly',
        'every 2 years' => 'every 2 years',
    ];
}
