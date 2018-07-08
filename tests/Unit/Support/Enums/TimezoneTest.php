<?php

namespace Tests\Unit\Support\Enums;

use App\Support\Enums\Timezone;
use Tests\TestCase;

class TimezoneTest extends TestCase
{
    /** @test */
    function all_timezones_are_valid()
    {
        $values = Timezone::selectValues();

        collect($values)
            ->values()
            ->map(function ($array) {
                return array_flip($array);
            })
            ->flatten()
            ->tap(function ($timezones) {
                $this->assertCount(411, $timezones);
            })
            ->each(function ($timezone) {
                now($timezone); // invalid timezones will throw an exception.
            })
            ->filter(function ($timezone) {
                return ! Timezone::has($timezone);
            })
            ->tap(function ($timezones) {
               $this->assertCount(0, $timezones);
            });
    }
}
