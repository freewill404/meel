<?php

namespace Tests\Unit\Listeners;

use App\Events\EmailSent;
use App\Listeners\DecrementUserEmailsLeft;
use App\Listeners\SetNextOccurrence;
use App\Mail\Email;
use App\Models\EmailSchedule;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DecrementUserEmailsLeftTest extends TestCase
{
    use RefreshDatabase;

    /** @var SetNextOccurrence */
    protected $decrementUserEmailsLeftListener;

    protected $emailMock;

    public function setUp()
    {
        parent::setUp();

        $this->withoutEvents();

        // Disable observers
        EmailSchedule::flushEventListeners();

        $this->decrementUserEmailsLeftListener = new DecrementUserEmailsLeft();

        $this->emailMock = new class extends Email {
            public function __construct() {}
        };
    }

    /** @test */
    function it_decrements_free_emails_before_paid_emails()
    {
        /** @var User $user */
        $user = factory(User::class)->create([
            'free_emails_left' => 2,
            'paid_emails_left' => 5,
        ]);

        /** @var EmailSchedule $schedule */
        $schedule = $user->emailSchedules()->create([
            'what' => 'The what text',
            'when' => 'every month',
        ]);

        $this->decrementUserEmailsLeftListener->handle(
            new EmailSent($schedule, $this->emailMock)
        );

        $user->refresh();
        $this->assertSame(1, $user->free_emails_left);
        $this->assertSame(5, $user->paid_emails_left);

        $this->decrementUserEmailsLeftListener->handle(
            new EmailSent($schedule, $this->emailMock)
        );

        $user->refresh();
        $this->assertSame(0, $user->free_emails_left);
        $this->assertSame(5, $user->paid_emails_left);

        $this->decrementUserEmailsLeftListener->handle(
            new EmailSent($schedule, $this->emailMock)
        );

        $user->refresh();
        $this->assertSame(0, $user->free_emails_left);
        $this->assertSame(4, $user->paid_emails_left);
    }
}
