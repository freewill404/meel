<?php

namespace Tests\Integration\Models;

use App\Events\EmailNotSent;
use App\Events\EmailSent;
use App\Jobs\SendScheduledEmailJob;
use App\Mail\AlmostOutOfEmailsEmail;
use App\Mail\Email;
use App\Mail\OutOfEmailsEmail;
use App\Models\EmailSchedule;
use App\Models\SiteStats;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class EmailScheduleTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function it_sends_emails()
    {
        $this->expectsEvents(EmailSent::class);

        $user = factory(User::class)->create();

        $emailSchedule = $user->emailSchedules()->create([
            'what' => 'The what text',
            'when' => 'in 1 minute',
        ]);

        $emailSchedule->sendEmail();

        Mail::assertSent(Email::class, function (Email $mail) use ($user) {
            return count($mail->to) === 1 && $mail->hasTo($user);
        });
    }

    /** @test */
    function now_emails_are_sent_immediately()
    {
        $this->expectsJobs(SendScheduledEmailJob::class);

        $user = factory(User::class)->create();

        $user->emailSchedules()->create([
            'what' => 'WHAT?',
            'when' => 'now',
        ]);
    }

    /** @test */
    function it_notifies_users_when_they_are_almost_out_of_emails()
    {
        $user = factory(User::class)->create([
            'free_emails_left' => 0,
            'paid_emails_left' => 10,
        ]);

        $user->emailSchedules()->create([
            'what' => 'The what text',
            'when' => 'now',
        ]);

        Mail::assertSent(AlmostOutOfEmailsEmail::class, function (AlmostOutOfEmailsEmail $mail) use ($user) {
            return count($mail->to) === 1 && $mail->hasTo($user);
        });
    }

    /** @test */
    function it_notifies_users_when_they_are_out_of_emails()
    {
        $user = factory(User::class)->create([
            'free_emails_left' => 0,
            'paid_emails_left' => 1,
        ]);

        $user->emailSchedules()->create([
            'what' => 'The what text',
            'when' => 'now',
        ]);

        Mail::assertSent(OutOfEmailsEmail::class, function (OutOfEmailsEmail $mail) use ($user) {
            return count($mail->to) === 1 && $mail->hasTo($user);
        });
    }

    /** @test */
    function users_with_no_emails_left_can_not_send_emails()
    {
        $this->expectsEvents(EmailNotSent::class);

        /** @var User $user */
        $user = factory(User::class)->create([
            'free_emails_left' => 0,
            'paid_emails_left' => 0,
        ]);

        /** @var EmailSchedule $schedule */
        $user->emailSchedules()->create([
            'what' => 'The what text',
            'when' => 'now',
        ]);
    }

    /** @test */
    function it_keeps_track_of_emails_sent()
    {
        $firstUser = factory(User::class)->create();

        $this->assertSame(0, SiteStats::today()->emails_sent);

        $this->assertSame(0, $firstUser->emails_sent);

        $firstUser->emailSchedules()->create([
            'what' => 'The what text',
            'when' => 'now',
        ]);

        $this->assertSame(1, SiteStats::today()->emails_sent);

        $this->assertSame(1, $firstUser->refresh()->emails_sent);

        $secondUser = factory(User::class)->create();

        $secondUser->emailSchedules()->create([
            'what' => 'The what text',
            'when' => 'now',
        ]);

        $this->assertSame(2, SiteStats::today()->emails_sent);

        $this->assertSame(1, $firstUser->refresh()->emails_sent);
    }

    /** @test */
    function it_creates_history_records_for_sent_emails()
    {
        $user = factory(User::class)->create(['timezone' => 'Asia/Shanghai']);

        /** @var EmailSchedule $emailSchedule */
        $emailSchedule = $user->emailSchedules()->create([
            'what' => 'The what text',
            'when' => 'in 1 minute',
        ]);

        $this->progressTimeInMinutes(1);

        $emailSchedule->sendEmail();

        $histories = $emailSchedule->emailScheduleHistories;

        $this->assertCount(1, $histories);

        $this->assertSame(
            '2018-03-28 18:01:00',
            (string) $histories->first()->sent_at
        );
    }

    /** @test */
    function it_sets_the_next_occurrence_for_emails_after_sending()
    {
        $user = factory(User::class)->create();

        /** @var EmailSchedule $emailSchedule */
        $emailSchedule = $user->emailSchedules()->create([
            'what' => 'The what text',
            'when' => 'every monday at 12:00',
        ]);

        $this->assertSame('2018-04-02 12:00:00', EmailSchedule::find(1)->next_occurrence);

        Carbon::setTestNow('2018-04-02 12:00:15');

        $emailSchedule->sendEmail();

        $this->assertSame('2018-04-09 12:00:00', EmailSchedule::find(1)->next_occurrence);
    }

    /** @test */
    function it_does_not_send_an_email_if_the_user_does_not_have_emails_left()
    {
        Notification::fake();

        $user = factory(User::class)->create([
            'emails_not_sent'  => 0,
            'free_emails_left' => 0,
            'paid_emails_left' => 0,
        ]);

        $this->assertSame(0, $user->emails_left);

        /** @var EmailSchedule $emailSchedule */
        $emailSchedule = $user->emailSchedules()->create([
            'what' => 'The what text',
            'when' => 'every month at 12:00',
        ]);

        $this->assertSame('2018-04-01 12:00:00', EmailSchedule::find(1)->next_occurrence);

        $this->assertSame(0, SiteStats::today()->emails_sent);
        $this->assertSame(0, SiteStats::today()->emails_not_sent);

        Carbon::setTestNow('2018-04-15 12:00:15');

        $emailSchedule->sendEmail();

        // The user has no emails left, so the email is not sent.
        Notification::assertNothingSent();

        // The next occurrence is set after not sending the email.
        $this->assertSame('2018-05-01 12:00:00', EmailSchedule::find(1)->next_occurrence);

        $this->assertSame(0, SiteStats::today()->emails_sent);
        $this->assertSame(1, SiteStats::today()->emails_not_sent);

        $this->assertSame(1, $user->refresh()->emails_not_sent);
    }

    /** @test */
    function it_gets_all_schedules_that_should_be_sent_right_now_respecting_timezones()
    {
        $amsterdamUser = factory(User::class)->create(['timezone' => 'Europe/Amsterdam']);
        $chinaUser     = factory(User::class)->create(['timezone' => 'Asia/Shanghai']);
        $londonUser    = factory(User::class)->create(['timezone' => 'Europe/London']);

        $chinaUser->emailSchedules()->create([    'what' => 'c1', 'when' => 'in 1 minute']);
        $londonUser->emailSchedules()->create([   'what' => 'l1', 'when' => 'in 1 minute']);
        $amsterdamUser->emailSchedules()->create(['what' => 'a1', 'when' => 'in 1 minute']);

        $chinaUser->emailSchedules()->create([    'what' => 'c2', 'when' => 'in 1 hour']);
        $londonUser->emailSchedules()->create([   'what' => 'l2', 'when' => 'in 1 hour']);
        $amsterdamUser->emailSchedules()->create(['what' => 'a2', 'when' => 'in 6 hours']);

        $this->progressTimeInMinutes(1);

        $schedules = EmailSchedule::shouldBeSentNow()->map(function (EmailSchedule $schedule) {
            return ['what' => $schedule->what, 'next_occurrence' => $schedule->next_occurrence];
        })->all();

        $this->assertSame([
            0 => ['what' => 'c1', 'next_occurrence' => '2018-03-28 18:01:00'],
            1 => ['what' => 'l1', 'next_occurrence' => '2018-03-28 11:01:00'],
            2 => ['what' => 'a1', 'next_occurrence' => '2018-03-28 12:01:00']
        ], $schedules);
    }
}
