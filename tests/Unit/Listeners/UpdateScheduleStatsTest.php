<?php

namespace Tests\Unit\Listeners;

use App\Events\ScheduledEmailNotSent;
use App\Events\ScheduledEmailSent;
use App\Listeners\UpdateScheduleStats;
use App\Mail\Email;
use App\Models\Schedule;
use App\Models\SiteStats;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UpdateScheduleStatsTest extends TestCase
{
    use RefreshDatabase;

    protected $emailMock;

    /** @var User */
    protected $user;

    public function setUp()
    {
        parent::setUp();

        // Disable observers and event
        Schedule::flushEventListeners();
        $this->withoutEvents();

        $this->user = factory(User::class)->create();

        $this->emailMock = new class extends Email {
            public function __construct() {}
        };
    }

    /** @test */
    function it_updates_schedule_stats_after_a_scheduled_email_is_sent()
    {
        /** @var Schedule $schedule */
        $schedule = $this->user->schedules()->create([
            'what'            => 'The what text',
            'when'            => 'every month',
            'last_sent_at'    => null,
            'times_sent'      => 0,
        ]);

        $this->assertSame(0, SiteStats::today()->scheduled_emails_sent);
        $this->assertSame(0, $this->user->scheduled_emails_sent);

        (new UpdateScheduleStats)->handle(
            new ScheduledEmailSent($schedule, $this->emailMock)
        );

        $this->assertSame(1, SiteStats::today()->scheduled_emails_sent);
        $this->assertSame(1, $this->user->refresh()->scheduled_emails_sent);

        $schedule->refresh();

        $this->assertSame('2018-03-28 12:00:00', (string) $schedule->last_sent_at);

        $this->assertSame(1, $schedule->times_sent);
    }

    /** @test */
    function it_updates_schedule_stats_after_a_scheduled_email_is_not_sent()
    {
        /** @var Schedule $schedule */
        $schedule = $this->user->schedules()->create([
            'what'            => 'The what text',
            'when'            => 'every month',
            'last_sent_at'    => null,
            'times_sent'      => 0,
        ]);

        $this->assertSame(0, SiteStats::today()->scheduled_emails_not_sent);
        $this->assertSame(0, $this->user->scheduled_emails_not_sent);

        (new UpdateScheduleStats)->handle(
            new ScheduledEmailNotSent($schedule)
        );

        $this->assertSame(1, SiteStats::today()->scheduled_emails_not_sent);
        $this->assertSame(1, $this->user->refresh()->scheduled_emails_not_sent);

        $schedule->refresh();

        $this->assertNull($schedule->last_sent_at);

        $this->assertSame(0, $schedule->times_sent);
    }
}
