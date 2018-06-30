<?php

namespace Tests\Unit\Listeners;

use App\Events\EmailSent;
use App\Listeners\SetNextOccurrence;
use App\Mail\Email;
use App\Models\EmailSchedule;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SetNextOccurrenceTest extends TestCase
{
    use RefreshDatabase;

    /** @var SetNextOccurrence */
    protected $setNextOccurrenceListener;

    protected $emailMock;

    /** @var User */
    protected $user;

    public function setUp()
    {
        parent::setUp();

        $this->withoutEvents();

        // Disable observers
        EmailSchedule::flushEventListeners();

        $this->setNextOccurrenceListener = new SetNextOccurrence();

        $this->user = factory(User::class)->create();

        $this->emailMock = new class extends Email {
            public function __construct() {}
        };
    }

    /** @test */
    function it_sets_the_next_occurrence_for_recurring_schedules()
    {
        /** @var EmailSchedule $emailSchedule */
        $emailSchedule = $this->user->emailSchedules()->create([
            'what'            => 'The what text',
            'when'            => 'every month',
            'next_occurrence' => 'NONE',
        ]);

        // Ensure the Observer didn't set the next occurrence
        $this->assertSame('NONE', $emailSchedule->refresh()->next_occurrence);

        $this->setNextOccurrenceListener->handle(
            new EmailSent($emailSchedule, $this->emailMock)
        );

        $this->assertSame(
            (string) next_occurrence($emailSchedule->when),
            $emailSchedule->refresh()->next_occurrence
        );
    }

    /** @test */
    function it_sets_the_next_occurrence_for_non_recurring_schedules_to_null()
    {
        /** @var EmailSchedule $emailSchedule */
        $emailSchedule = $this->user->emailSchedules()->create([
            'what'            => 'The what text',
            'when'            => 'now',
            'next_occurrence' => 'NONE',
        ]);

        // Ensure the Observer didn't set the next occurrence
        $this->assertSame('NONE', $emailSchedule->refresh()->next_occurrence);

        $this->setNextOccurrenceListener->handle(
            new EmailSent($emailSchedule, $this->emailMock)
        );

        $this->assertNull($emailSchedule->refresh()->next_occurrence);
    }

    /** @test */
    function regression__it_uses_the_users_timezone()
    {
        $user = factory(User::class)->create(['timezone' => 'Asia/Shanghai']);

        /** @var EmailSchedule $emailSchedule */
        $emailSchedule = $user->emailSchedules()->create([
            'what'            => 'The what text',
            'when'            => 'every month',
            'next_occurrence' => 'NONE',
        ]);

        // Ensure the Observer didn't set the next occurrence
        $this->assertSame('NONE', $emailSchedule->refresh()->next_occurrence);

        $this->setNextOccurrenceListener->handle(
            new EmailSent($emailSchedule, $this->emailMock)
        );

        $this->assertSame(
            (string) next_occurrence($emailSchedule->when, 'Asia/Shanghai'),
            $emailSchedule->refresh()->next_occurrence
        );

        // Set the server time (Europe/Amsterdam) to the time the email schedule should be sent.
        Carbon::setTestNow(
            Carbon::parse($emailSchedule->next_occurrence, 'Asia/Shanghai')->setTimezone('Europe/Amsterdam')->addSeconds(15)
        );

        $this->setNextOccurrenceListener->handle(
            new EmailSent($emailSchedule, $this->emailMock)
        );

        $this->assertSame(
            (string) next_occurrence($emailSchedule->when, 'Asia/Shanghai'),
            $emailSchedule->refresh()->next_occurrence
        );
    }
}
