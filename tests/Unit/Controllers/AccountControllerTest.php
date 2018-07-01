<?php

namespace Tests\Unit\Controllers;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AccountControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function it_updates_a_users_timezone()
    {
        $user = factory(User::class)->create([
            'timezone' => 'Europe/Amsterdam',
        ]);

        $this->actingAs($user)
            ->post(route('user.account.settings.updateTimezone'), [
                'timezone' => 'UTC',
            ])
            ->assertSessionHasNoErrors();

        $this->assertSame('UTC', $user->refresh()->timezone);
    }

    /** @test */
    function it_updates_a_users_password()
    {
        $user = factory(User::class)->create();

        $oldHash = $user->password;

        $this->actingAs($user)
            ->post(route('user.account.settings.updatePassword'), [
                'new_password'              => 'hunter2',
                'new_password_confirmation' => 'hunter2',
            ])
            ->assertSessionHasNoErrors();

        $this->assertNotSame($oldHash, $user->refresh()->password);

        $this->assertTrue(
            Hash::check('hunter2', $user->password)
        );
    }
}
