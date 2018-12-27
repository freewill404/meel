<?php

namespace Tests\Unit\Controllers;

use App\Mail\ConfirmAccountEmail;
use App\Models\SiteStats;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class RegisterControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function it_registers_users_and_sends_the_confirm_account_email()
    {
        $this->registerUser()
            ->assertStatus(302)
            ->assertRedirect(route('register.done'));

        $user = User::findOrFail(1);

        // Users should confirm their email after registering
        $this->assertSame(false, $user->email_confirmed);

        $this->assertNotSame(null, $user->email_confirm_token);

        Mail::assertQueued(ConfirmAccountEmail::class, 1);

        Mail::assertQueued(ConfirmAccountEmail::class, function (ConfirmAccountEmail $mail) use ($user) {
            return count($mail->to) === 1 && $mail->hasTo($user->email);
        });
    }

    /** @test */
    function it_confirms_accounts_when_visiting_the_link_with_a_token()
    {
        factory(User::class)->create([
            'email_confirm_token' => 'UNIQUE_TOKEN',
            'email_confirmed' => false,
        ]);

        $this->get(route('register.confirm').'?token=UNIQUE_TOKEN')
            ->assertStatus(302)
            ->assertRedirect(route('home'));

        $user = User::findOrFail(1);

        $this->assertSame(true, $user->email_confirmed);

        $this->assertSame(null, $user->email_confirm_token);
    }

    /** @test */
    function it_keeps_track_of_users_registered()
    {
        $this->registerUser()->assertRedirect(route('register.done'));

        $this->assertSame(1, SiteStats::today()->users_registered);

        $this->registerUser([
            'email' => 'new@example.com',
        ])->assertRedirect(route('register.done'));

        $this->assertSame(2, SiteStats::today()->users_registered);
    }

    private function registerUser($data = [])
    {
        return $this->post(route('register.post'), $data + [
            'email' => 'test@example.com',
            'timezone'=> 'Europe/Amsterdam',
            'password' => 'secret',
            'password_confirmation' => 'secret',
        ]);
    }
}
