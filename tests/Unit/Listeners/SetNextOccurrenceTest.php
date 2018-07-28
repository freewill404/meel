<?php

namespace Tests\Unit\Listeners;

use App\Events\ScheduledEmailSent;
use App\Listeners\SetNextOccurrence;
use App\Mail\Email;
use App\Models\Schedule;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SetNextOccurrenceTest extends TestCase
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

        // Disable observers and events
        Schedule::flushEventListeners();
        $this->withoutEvents();

        $this->listener = new SetNextOccurrence();

        $this->user = factory(User::class)->create();

        $this->emailMock = new class extends Email {
            public function __construct() {}
        };
    }

    /** @test */
    function it_sets_the_next_occurrence_for_recurring_schedules()
    {
        /** @var Schedule $schedule */
        $schedule = $this->user->schedules()->create([
            'what' => 'The what text',
            'when' => 'every month',
        ]);

        // Ensure the Observer didn't set the next occurrence
        $this->assertNull($schedule->refresh()->next_occurrence);

        $this->listener->handle(
            new ScheduledEmailSent($schedule, $this->emailMock)
        );

        $this->assertSame(
            '2018-04-01 08:00:00',
            (string) $schedule->refresh()->next_occurrence
        );
    }

    /** @test */
    function it_sets_the_next_occurrence_for_non_recurring_schedules_to_null()
    {
        /** @var Schedule $schedule */
        $schedule = $this->user->schedules()->create([
            'what' => 'The what text',
            'when' => 'now',
        ]);

        $this->listener->handle(
            new ScheduledEmailSent($schedule, $this->emailMock)
        );

        $this->assertNull($schedule->refresh()->next_occurrence);
    }

    /** @test */
    function it_stores_the_next_occurrence_in_the_servers_timezone()
    {
        $user = factory(User::class)->create(['timezone' => 'Asia/Shanghai']);

        /** @var Schedule $schedule */
        $schedule = $user->schedules()->create([
            'what' => 'The what text',
            'when' => 'every day at 12:00',
        ]);

        $this->listener->handle(
            new ScheduledEmailSent($schedule, $this->emailMock)
        );

        $this->assertSame(
            '2018-03-29 06:00:00', // 06:00 server time is 12:00 China time
            (string) $schedule->refresh()->next_occurrence
        );
    }
}
