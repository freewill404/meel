<?php

namespace Tests\Unit\Meel\When\Formats\Relative;

use App\Meel\When\Formats\Relative\RelativeNow;

class RelativeNowTest extends RelativeWhenFormatTestCase
{
    protected $whenFormat = RelativeNow::class;

    protected $shouldMatch = [
        '2018-05-15 12:00:00' => [
            'now' => '2018-05-15 12:00:00',
            'right now' => '2018-05-15 12:00:00',
        ],
    ];

    protected $shouldNotMatch = [
        '2018-05-15 12:00:00' => [
            'nowadays',
            '1 month from now',
        ],
    ];
}
