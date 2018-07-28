<?php

namespace Tests\Unit\Listeners;

use App\Events\ScheduledEmailSent;
use App\Listeners\SetNextOccurrence;
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

    /** @var SetNextOccurrence */
    protected $listener;

    protected $emailMock;

    /** @var User */
    protected $user;

    public function setUp()
    {
        parent::setUp();

        $this->withoutEvents();

        // Disable observers
        Schedule::flushEventListeners();

        $this->listener = new UpdateScheduleStats();

        $this->user = factory(User::class)->create();

        $this->emailMock = new class extends Email {
            public function __construct() {}
        };
    }

    /** @test */
    function it_updates_schedule_stats_after_an_email_is_sent()
    {
        /** @var Schedule $schedule */
        $schedule = $this->user->schedules()->create([
            'what'            => 'The what text',
            'when'            => 'every month',
            'last_sent_at'    => null,
            'times_sent'      => 0,
        ]);

        $this->assertSame(0, SiteStats::today()->emails_sent);

        $this->listener->handle(
            new ScheduledEmailSent($schedule, $this->emailMock)
        );

        $this->assertSame(1, SiteStats::today()->emails_sent);

        $schedule->refresh();

        $this->assertSame('2018-03-28 12:00:00', (string) $schedule->last_sent_at);

        $this->assertSame(1, $schedule->times_sent);
    }
}
