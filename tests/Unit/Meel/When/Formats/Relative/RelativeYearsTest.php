<?php

namespace Tests\Unit\Meel\When\Formats\Relative;

use App\Meel\When\Formats\Relative\RelativeYears;

class RelativeYearsTest extends RelativeWhenFormatTestCase
{
    protected $whenFormat = RelativeYears::class;

    protected $shouldMatch = [
        '2018-05-15 12:00:00' => [
            'next year' => '2019-05-15 12:00:00',
            'in 1 years' => '2019-05-15 12:00:00',
            'in 2 years' => '2020-05-15 12:00:00',

            'in 1 month and 1 year' => '2019-05-15 12:00:00',
            '2 year from now' => '2020-05-15 12:00:00',
            '2 years and 1 month from now' => '2020-05-15 12:00:00',

            'in a decade' => '2028-05-15 12:00:00',
            'a decade from now' => '2028-05-15 12:00:00',

            'a millennium from now' => '3018-05-15 12:00:00',
        ],
    ];

    protected $shouldNotMatch = [
        '2018-05-15 12:00:00' => [
            'last year',
            'in 0 years',
            '2 years',
        ],
    ];
}
