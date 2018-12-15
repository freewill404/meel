<?php

namespace Tests\Unit\Meel\What\Formats;

use App\Meel\What\Formats\WhatFormat;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

abstract class WhatFormatTestCase extends TestCase
{
    use RefreshDatabase;

    protected $whatFormat;

    protected function assertFormattedWhen($expected, $rawWhen, $schedule)
    {
        /** @var WhatFormat $whatFormat */
        $whatFormat = new $this->whatFormat($schedule);

        $this->assertSame(
            $expected,
            $whatFormat->applyFormat($rawWhen)
        );
    }

    protected function assertNoFormatApplied($rawWhen, $schedule)
    {
        $this->assertFormattedWhen($rawWhen, $rawWhen, $schedule);
    }

    protected function createUserAndSchedule($timezone = 'Europe/Amsterdam')
    {
        $user = factory(User::class)->create([
            'timezone' => $timezone,
        ]);

        $schedule = $user->schedules()->create([
            'what' => 'no what',
            'when' => 'in 1 hour',
        ]);

        return [$user, $schedule];
    }
}
