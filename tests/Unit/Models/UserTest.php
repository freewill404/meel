<?php

namespace Tests\Unit\Models;

use App\Events\EmailNotSent;
use App\Events\EmailSent;
use App\Mail\AlmostOutOfEmailsEmail;
use App\Mail\OutOfEmailsEmail;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Mail\Mailable;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function it_queues_emails()
    {
        $this->expectsEvents(EmailSent::class);

        /** @var User $user */
        $user = factory(User::class)->create();

        $user->sendEmail(new class extends Mailable {
            public $identifier = 'THIS ONE!';
        });

        Mail::assertQueued(Mailable::class, function ($email) {
            return $email->identifier === 'THIS ONE!';
        });
    }

    /** @test */
    function it_fires_an_email_not_sent_event_when_the_user_has_no_emails_left()
    {
        $this->expectsEvents(EmailNotSent::class);

        /** @var User $user */
        $user = factory(User::class)->create(['emails_left' => 0]);

        $user->sendEmail(new Mailable);

        Mail::assertNothingQueued();
    }

    /** @test */
    function it_notifies_users_when_they_are_almost_out_of_emails()
    {
        $user = factory(User::class)->create([
            'emails_left' => 10,
        ]);

        $user->sendEmail(new Mailable);

        Mail::assertQueued(AlmostOutOfEmailsEmail::class, 1);

        Mail::assertQueued(AlmostOutOfEmailsEmail::class, function (AlmostOutOfEmailsEmail $mail) use ($user) {
            return count($mail->to) === 1 && $mail->hasTo($user);
        });
    }

    /** @test */
    function it_notifies_users_when_they_are_out_of_emails()
    {
        $user = factory(User::class)->create([
            'emails_left' => 1,
        ]);

        $user->sendEmail(new Mailable);

        Mail::assertQueued(OutOfEmailsEmail::class, 1);

        Mail::assertQueued(OutOfEmailsEmail::class, function (OutOfEmailsEmail $mail) use ($user) {
            return count($mail->to) === 1 && $mail->hasTo($user);
        });
    }

    /** @test */
    function it_correctly_handles_users_running_out_of_emails_when_sending_multiple_in_a_row()
    {
        /** @var User $user */
        $user = factory(User::class)->create(['emails_left' => 1]);

        $user->sendEmail(new class extends Mailable {
            public $identifier = 'THIS ONE!';
        });

        $user->sendEmail(new class extends Mailable {
            public $identifier = 'NOT THIS ONE!';
        });

        Mail::assertQueued(Mailable::class, function ($email) {
            return ($email->identifier ?? null) === 'THIS ONE!';
        });

        Mail::assertNotQueued(Mailable::class, function ($email) {
            return ($email->identifier ?? null) === 'NOT THIS ONE!';
        });
    }

    /** @test */
    function it_computes_emails_sent()
    {
        $user = factory(User::class)->create([
            'scheduled_emails_sent' => 2,
            'feed_emails_sent'      => 3,
        ]);

        $this->assertSame(5, $user->emails_sent);
        $this->assertSame(0, $user->emails_not_sent);
    }

    /** @test */
    function it_computes_emails_not_sent()
    {
        $user = factory(User::class)->create([
            'scheduled_emails_not_sent' => 2,
            'feed_emails_not_sent'      => 3,
        ]);

        $this->assertSame(0, $user->emails_sent);
        $this->assertSame(5, $user->emails_not_sent);
    }


}
