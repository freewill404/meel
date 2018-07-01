<?php

namespace Tests\Unit\WhatFormats;

use App\Meel\WhatFormats\WhatFormat;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

abstract class WhatFormatTestCase extends TestCase
{
    use RefreshDatabase;

    protected $whatFormat;

    protected function assertFormattedWhen($expected, $rawWhen, $emailSchedule)
    {
        /** @var WhatFormat $whatFormat */
        $whatFormat = new $this->whatFormat($emailSchedule);

        $this->assertSame(
            $expected,
            $whatFormat->applyFormat($rawWhen)
        );
    }

    protected function assertNoFormatApplied($rawWhen, $emailSchedule)
    {
        $this->assertFormattedWhen($rawWhen, $rawWhen, $emailSchedule);
    }

    protected function createUserAndSchedule($timezone = 'Europe/Amsterdam')
    {
        $user = factory(User::class)->create([
            'timezone' => $timezone,
        ]);

        $emailSchedule = $user->emailSchedules()->create([
            'what' => 'no what',
            'when' => 'in 1 hour',
        ]);

        return [$user, $emailSchedule];
    }
}
