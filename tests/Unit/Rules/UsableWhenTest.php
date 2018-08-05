<?php

namespace Tests\Unit\Rules;

use App\Http\Rules\UsableWhen;
use Tests\TestCase;

class UsableWhenTest extends TestCase
{
    /** @test */
    function it_passes_null()
    {
        $this->assertTrue(
            (new UsableWhen)->passes('when', null)
        );
    }

    /** @test */
    function it_passes_valid_non_recurring_whens()
    {
        $this->assertTrue(
            (new UsableWhen)->passes('when', 'now')
        );
    }

    /** @test */
    function it_passes_valid_recurring_whens()
    {
        $this->assertTrue(
            (new UsableWhen)->passes('when', 'every day')
        );
    }

    /** @test */
    function it_fails_invalid_whens()
    {
        $this->assertFalse(
            (new UsableWhen)->passes('when', 'durr')
        );
    }
}
