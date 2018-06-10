<?php

namespace Tests\Unit\WhenFormats\Relative;

use App\Meel\WhenFormats\Relative\RelativeYears;

class RelativeYearsTest extends RelativeWhenFormatTestCase
{
    protected $whenFormat = RelativeYears::class;

    protected $shouldMatch = [
        'next year',
        'in 1 month and 1 year',
        'in 2 years',
        '2 year from now',
        '2 years and 1 month from now',
    ];

    protected $shouldNotMatch = [
        'last year',
        'in 0 years',
        '2 years',
    ];
}
