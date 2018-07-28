<?php

namespace Tests\Unit\Support\Enums;

use App\Support\Enums\UserRole;
use Tests\TestCase;

class UserRoleTest extends TestCase
{
    /** @test */
    function all_values_are_unique()
    {
        $this->assertSame(
            UserRole::values()->all(),
            UserRole::values()->unique()->all(),
            'Not all enum values are unique'
        );
    }

    /** @test */
    function all_values_should_be_lowercase()
    {
        $allValues = UserRole::values();

        $lowercaseValues = $allValues->filter(function (string $value) {
            return strtolower($value) === $value;
        })->values()->all();

        $this->assertSame(
            $allValues->all(),
            $lowercaseValues,
            'Enum values should all be lowercase'
        );
    }

    /** @test */
    function values_should_not_contain_commas_or_pipes()
    {
        // Commas or pipes will break the "required()" and "optional()" validation rules.
        $allValues = UserRole::values();

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
