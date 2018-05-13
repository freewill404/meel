<?php

namespace Tests\Integration;

use App\Mail\ConfirmAccountEmail;
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
        $this->post(route('register.post'), [
                'email'                 => 'test@example.com',
                'timezone'              => 'Asia/Shanghai',
                'password'              => 'secret',
                'password_confirmation' => 'secret',
            ])
            ->assertStatus(302)
            ->assertRedirect(route('register.done'));

        $user = User::findOrFail(1);

        // Users should confirm their email after registering
        $this->assertSame(false, $user->email_confirmed);

        $this->assertNotSame(null, $user->email_confirm_token);

        Mail::assertSent(ConfirmAccountEmail::class, function (ConfirmAccountEmail $mail) use ($user) {
            return $mail->hasTo($user->email);
        });
    }

    /** @test */
    function it_confirms_accounts_when_visiting_the_link_with_a_token()
    {
        factory(User::class)->create([
            'email_confirm_token' => 'UNIQUE_TOKEN',
            'email_confirmed'     => false,
        ]);

        $this->get(route('register.confirm').'?token=UNIQUE_TOKEN')
            ->assertStatus(302)
            ->assertRedirect(route('home'));

        $user = User::findOrFail(1);

        $this->assertSame(true, $user->email_confirmed);

        $this->assertSame(null, $user->email_confirm_token);
    }
}
