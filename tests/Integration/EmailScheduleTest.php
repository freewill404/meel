<?php

namespace Tests\Integration;

use App\Mail\Email;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class EmailScheduleTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function it_sends_emails()
    {
        $user = factory(User::class)->create();

        $emailSchedule = $user->emailSchedules()->create([
            'what' => 'The what text',
            'when' => 'now',
        ]);

        $emailSchedule->sendEmail();

        Mail::assertSent(Email::class, function (Email $mail) use ($user) {
            return $mail->hasTo($user->email) && $mail->subject === 'The what text | Meel.me';
        });
    }

    /** @test */
    function it_creates_history_records_for_sent_emails()
    {
        $user = factory(User::class)->create();

        $emailSchedule = $user->emailSchedules()->create([
            'what' => 'The what text',
            'when' => 'now',
        ]);

        $emailSchedule->sendEmail();

        $histories = $emailSchedule->emailScheduleHistories;

        $this->assertSame(1, $histories->count());

        $this->assertSame(
            (string) $histories->first()->sent_at,
            '2018-03-28 12:00:00'
        );
    }
}
