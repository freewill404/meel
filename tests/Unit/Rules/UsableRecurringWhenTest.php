<?php

namespace Tests\Unit\Rules;

use App\Http\Rules\UsableRecurringWhen;
use Tests\TestCase;

class UsableRecurringWhenTest extends TestCase
{
    /** @test */
    function it_passes_null()
    {
        $this->assertTrue(
            (new UsableRecurringWhen)->passes('when', null)
        );
    }

    /** @test */
    function it_fails_valid_non_recurring_whens()
    {
        $this->assertFalse(
            (new UsableRecurringWhen)->passes('when', 'now')
        );
    }

    /** @test */
    function it_passes_valid_recurring_whens()
    {
        $this->assertTrue(
            (new UsableRecurringWhen)->passes('when', 'every day')
        );
    }

    /** @test */
    function it_fails_invalid_whens()
    {
        $this->assertFalse(
            (new UsableRecurringWhen)->passes('when', 'durr')
        );
    }
}
