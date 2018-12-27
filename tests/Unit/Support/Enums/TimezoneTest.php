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
                $this->assertCount(492, $timezones);
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

    /** @test */
    function all_values_are_unique()
    {
        $this->assertSame(
            Timezone::values()->all(),
            Timezone::values()->unique()->all(),
            'Not all enum values are unique'
        );
    }

    /** @test */
    function values_should_not_contain_commas_or_pipes()
    {
        // Commas or pipes will break the "required()" and "optional()" validation rules.
        $allValues = Timezone::values();

        $goodValues = $allValues->filter(function (string $value) {
            return strpos($value, ',') === false && strpos($value, '|') === false;
        })->values()->all();

        $this->assertSame(
            $allValues->all(),
            $goodValues,
            'Enum values should not contain commas or pipes'
        );
    }
}
