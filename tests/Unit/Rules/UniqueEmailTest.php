<?php

namespace Tests\Unit\Rules;

use App\Http\Rules\UniqueEmail;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UniqueEmailTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function it_prevents_the_same_email_from_registering_twice()
    {
        $this->assertUniqueEmailPasses('test@example.com')
            ->createUser('test@example.com')
            ->assertUniqueEmailFails('test@example.com');
    }

    /** @test */
    function it_is_case_insensitive()
    {
        $this->createUser('test@example.com')
            ->assertUniqueEmailFails('TEST@example.com');
    }

    /** @test */
    function it_ignores_plus_signs()
    {
        $this->assertUniqueEmailPasses('test+1@example.com')
            ->createUser('test@example.com')
            ->assertUniqueEmailFails('test+1@example.com');

        $this->createUser('another+test+1@example.com')
            ->assertUniqueEmailFails('another@example.com');
    }

    /** @test */
    function it_ignores_dots()
    {
        $this->assertUniqueEmailPasses('te.st@example.com')
            ->createUser('test@example.com')
            ->assertUniqueEmailFails('te.st@example.com');

        $this->createUser('an.other@example.com')
            ->assertUniqueEmailFails('another@example.com');
    }

    private function createUser($email)
    {
        factory(User::class)->create([
            'email' => $email,
        ]);

        return $this;
    }

    private function assertUniqueEmailPasses($value)
    {
        $this->assertTrue(
            (new UniqueEmail)->passes('email', $value)
        );

        return $this;
    }

    private function assertUniqueEmailFails($value)
    {
        $this->assertFalse(
            (new UniqueEmail)->passes('email', $value)
        );

        return $this;
    }
}
