<?php

namespace Tests\Integration;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LoginControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function unconfirmed_users_can_not_login()
    {
        factory(User::class)->create([
            'email'               => 'a@a.nl',
            'password'            => '$2y$10$TKh8H1.PfQx37YgCzwiKb.KjNyWgaHb9cbcoQgdIVFlYg7B77UdFm', // secret
            'email_confirm_token' => 'UNIQUE_TOKEN',
            'email_confirmed'     => false,
        ]);

        $this->post(route('login'), [
                'email'    => 'a@a.nl',
                'password' => 'secret',
            ])
            ->assertSessionHasErrors();

        $this->assertGuest();

        $this->get(route('register.confirm').'?token=UNIQUE_TOKEN')
            ->assertStatus(302)
            ->assertRedirect(route('home'));

        $this->assertAuthenticated();

        $this->post(route('login'), [
                'email'    => 'a@a.nl',
                'password' => 'secret',
            ])
            ->assertRedirect(route('home'));
    }

    /** @test */
    function users_with_an_unconfirmed_email_can_never_be_authenticated()
    {
        $user = factory(User::class)->create([
            'email_confirm_token' => 'UNIQUE_TOKEN',
            'email_confirmed'     => false,
        ]);

        $this->actingAs($user)
            ->get(route('home'))
            ->assertStatus(403)
            ->assertSeeText('Email not confirmed');

        $this->assertGuest();
    }
}
