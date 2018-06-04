<?php

namespace Tests\Integration;

use App\Events\EmailSent;
use App\Jobs\SendScheduledEmailJob;
use App\Mail\Email;
use App\Models\EmailSchedule;
use App\Models\SiteStats;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
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

        $this->progressTimeInMinutes(1);

        $emailSchedule->sendEmail();

        Mail::assertSent(Email::class, function (Email $mail) use ($user) {
            return $mail->hasTo($user->email) && $mail->subject === 'The what text';
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
    function it_keeps_track_of_emails_sent()
    {
        $user = factory(User::class)->create();

        $this->assertSame(0, SiteStats::today()->emails_sent);

        $user->emailSchedules()->create([
            'what' => 'The what text',
            'when' => 'now',
        ]);

        $this->assertSame(1, SiteStats::today()->emails_sent);

        $user->emailSchedules()->create([
            'what' => 'The what text',
            'when' => 'now',
        ]);

        $this->assertSame(2, SiteStats::today()->emails_sent);
    }

    /** @test */
    function it_creates_history_records_for_sent_emails()
    {
        $user = factory(User::class)->create(['timezone' => 'Asia/Shanghai']);

        $emailSchedule = $user->emailSchedules()->create([
            'what' => 'The what text',
            'when' => 'in 1 minute',
        ]);

        $this->progressTimeInMinutes(1);

        $emailSchedule->sendEmail();

        $histories = $emailSchedule->emailScheduleHistories;

        $this->assertSame(1, $histories->count());

        $this->assertSame(
            '2018-03-28 18:01:00',
            (string) $histories->first()->sent_at
        );

        $this->assertSame(
            '2018-03-28 12:01:00',
            (string) $histories->first()->sent_at_server_time
        );
    }

    /** @test */
    function it_sets_the_next_occurrence_for_recurring_emails_after_sending()
    {
        $user = factory(User::class)->create();

        $emailSchedule = $user->emailSchedules()->create([
            'what' => 'The what text',
            'when' => 'every monday at 12:00',
        ]);

        $this->assertSame('2018-04-02 12:00:00', EmailSchedule::find(1)->next_occurrence);

        Carbon::setTestNow('2018-04-02 12:00:00');

        $emailSchedule->sendEmail();

        $this->assertSame('2018-04-09 12:00:00', EmailSchedule::find(1)->next_occurrence);
    }

    /** @test */
    function it_sets_the_next_occurrence_for_non_recurring_emails_to_null()
    {
        $user = factory(User::class)->create();

        $emailSchedule = $user->emailSchedules()->create([
            'what' => 'The what text',
            'when' => 'in 1 minute',
        ]);

        $this->assertSame('2018-03-28 12:01:00', EmailSchedule::find(1)->next_occurrence);

        $emailSchedule->sendEmail();

        $this->assertSame(null, EmailSchedule::find(1)->next_occurrence);
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
