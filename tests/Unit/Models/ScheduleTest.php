<?php

namespace Tests\Unit\Models;

use App\Events\EmailNotSent;
use App\Events\ScheduledEmailNotSent;
use App\Events\ScheduledEmailSent;
use App\Mail\Email;
use App\Models\Schedule;
use App\Models\SiteStats;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class ScheduleTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function it_sends_emails()
    {
        $this->expectsEvents(ScheduledEmailSent::class);

        $user = factory(User::class)->create();

        $schedule = $user->schedules()->create([
            'what' => 'The what text',
            'when' => 'in 1 minute',
        ]);

        $schedule->sendEmail();

        Mail::assertQueued(Email::class, 1);

        Mail::assertQueued(Email::class, function (Email $mail) use ($user) {
            return count($mail->to) === 1 && $mail->hasTo($user);
        });
    }

    /** @test */
    function now_emails_are_sent_immediately()
    {
        $user = factory(User::class)->create();

        $user->schedules()->create([
            'what' => 'WHAT?',
            'when' => 'now',
        ]);

        Mail::assertQueued(Email::class, 1);

        Mail::assertQueued(Email::class, function (Email $mail) use ($user) {
            return count($mail->to) === 1 && $mail->hasTo($user);
        });
    }

    /** @test */
    function users_with_no_emails_left_can_not_send_emails()
    {
        $this->expectsEvents([
            EmailNotSent::class,
            ScheduledEmailNotSent::class,
        ]);

        /** @var User $user */
        $user = factory(User::class)->create([
            'emails_left' => 0,
        ]);

        /** @var Schedule $schedule */
        $user->schedules()->create([
            'what' => 'The what text',
            'when' => 'now',
        ]);
    }

    /** @test */
    function it_keeps_track_of_scheduled_emails_sent()
    {
        $firstUser = factory(User::class)->create();

        $this->assertSame(0, SiteStats::today()->scheduled_emails_sent);
        $this->assertSame(0, $firstUser->scheduled_emails_sent);

        $firstUser->schedules()->create([
            'what' => 'The what text',
            'when' => 'now',
        ]);

        $this->assertSame(1, SiteStats::today()->scheduled_emails_sent);
        $this->assertSame(1, $firstUser->refresh()->scheduled_emails_sent);

        $secondUser = factory(User::class)->create();

        $secondUser->schedules()->create([
            'what' => 'The what text',
            'when' => 'now',
        ]);

        $this->assertSame(2, SiteStats::today()->scheduled_emails_sent);
        $this->assertSame(1, $firstUser->refresh()->scheduled_emails_sent);
        $this->assertSame(1, $secondUser->refresh()->scheduled_emails_sent);
    }

    /** @test */
    function it_keeps_track_of_when_the_last_email_was_sent()
    {
        $user = factory(User::class)->create(['timezone' => 'Asia/Shanghai']);

        /** @var Schedule $schedule */
        $schedule = $user->schedules()->create([
            'what' => 'The what text',
            'when' => 'in 1 minute',
        ]);

        $this->progressTimeInMinutes(1);

        $schedule->sendEmail();

        $this->assertSame('2018-03-28 12:01:00', (string) $schedule->refresh()->last_sent_at);

        $schedule = $user->schedules()->create([
            'what' => 'The what text',
            'when' => 'in 1 day',
        ]);

        $this->progressTimeInDays(1);

        $schedule->sendEmail();

        $this->assertSame('2018-03-29 12:01:00', (string) $schedule->refresh()->last_sent_at);
    }

    /** @test */
    function it_formats_the_what_string()
    {
        $user = factory(User::class)->create();

        /** @var Schedule $schedule */
        $schedule = $user->schedules()->create([
            'what' => 'format: %t',
            'when' => 'every monday at 12:00',
        ]);

        $this->assertSame('format: 1', $schedule->formatted_what);

        $this->assertSame('format: %t', $schedule->what);
    }

    /** @test */
    function it_sets_the_next_occurrence_in_server_time_when_creating()
    {
        $user = factory(User::class)->create(['timezone' => 'Asia/Shanghai']);

        /** @var Schedule $schedule */
        $schedule = $user->schedules()->create([
            'what' => 'The what text',
            'when' => 'every day at 12:00',
        ]);

        $this->assertSame(
            '2018-03-29 06:00:00', // 06:00 server time is 12:00 China time
            (string) $schedule->next_occurrence
        );
    }

    /** @test */
    function it_sets_the_next_occurrence_for_emails_after_sending()
    {
        $user = factory(User::class)->create();

        /** @var Schedule $schedule */
        $schedule = $user->schedules()->create([
            'what' => 'The what text',
            'when' => 'every monday at 12:00',
        ]);

        $this->assertSame(
            '2018-04-02 12:00:00',
            (string) $schedule->refresh()->next_occurrence
        );

        Carbon::setTestNow('2018-04-02 12:00:15');

        $schedule->sendEmail();

        $this->assertSame(
            '2018-04-09 12:00:00',
            (string) $schedule->refresh()->next_occurrence
        );
    }

    /** @test */
    function it_does_not_send_an_email_if_the_user_does_not_have_emails_left()
    {
        Notification::fake();

        $user = factory(User::class)->create([
            'scheduled_emails_not_sent' => 0,
            'emails_left'               => 0,
        ]);

        $this->assertSame(0, $user->emails_left);

        /** @var Schedule $schedule */
        $schedule = $user->schedules()->create([
            'what' => 'The what text',
            'when' => 'every month at 12:00',
        ]);

        $this->assertSame(
            '2018-04-01 12:00:00',
            (string) $schedule->refresh()->next_occurrence
        );

        $this->assertSame(0, SiteStats::today()->scheduled_emails_sent);
        $this->assertSame(0, SiteStats::today()->scheduled_emails_not_sent);

        Carbon::setTestNow('2018-04-15 12:00:15');

        $schedule->sendEmail();

        // The user has no emails left, so the email is not sent.
        Mail::assertNothingSent();

        // The next occurrence is set after not sending the email.
        $this->assertSame(
            '2018-05-01 12:00:00',
            (string) $schedule->refresh()->next_occurrence
        );

        $this->assertSame(0, SiteStats::today()->scheduled_emails_sent);
        $this->assertSame(1, SiteStats::today()->scheduled_emails_not_sent);

        $this->assertSame(1, $user->refresh()->scheduled_emails_not_sent);
    }

    /** @test */
    function it_gets_all_schedules_that_should_be_sent_right_now()
    {
        $amsterdamUser = factory(User::class)->create(['timezone' => 'Europe/Amsterdam']);
        $chinaUser     = factory(User::class)->create(['timezone' => 'Asia/Shanghai']);
        $londonUser    = factory(User::class)->create(['timezone' => 'Europe/London']);

        $chinaUser->schedules()->create([    'what' => 'c1', 'when' => 'at 18:01']);
        $londonUser->schedules()->create([   'what' => 'l1', 'when' => 'at 11:01']);
        $amsterdamUser->schedules()->create(['what' => 'a1', 'when' => 'at 12:01']);

        $this->progressTimeInMinutes(1);

        $schedules = Schedule::shouldBeSentNow()
            ->get()
            ->map(function (Schedule $schedule) {
                return ['what' => $schedule->what, 'next_occurrence' => (string) $schedule->next_occurrence];
            })
            ->all();

        $this->assertSame([
            0 => ['what' => 'c1', 'next_occurrence' => '2018-03-28 12:01:00'],
            1 => ['what' => 'l1', 'next_occurrence' => '2018-03-28 12:01:00'],
            2 => ['what' => 'a1', 'next_occurrence' => '2018-03-28 12:01:00'],
        ], $schedules);
    }
}
