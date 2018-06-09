<?php

namespace Tests\Unit\Listeners;

use App\Events\EmailSent;
use App\Events\UserAlmostOutOfEmails;
use App\Events\UserOutOfEmails;
use App\Listeners\DecrementUserEmailsLeft;
use App\Mail\Email;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class DecrementUserEmailsLeftTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function it_decrements_free_emails_before_paid_emails()
    {
        [$user, $schedule] = $this->createUserAndSchedule(2, 5);

        $this->assertSame(7, $user->emails_left);

        $this->handleEvent($schedule);

        $user->refresh();
        $this->assertSame(1, $user->free_emails_left);
        $this->assertSame(5, $user->paid_emails_left);

        $this->handleEvent($schedule);

        $user->refresh();
        $this->assertSame(0, $user->free_emails_left);
        $this->assertSame(5, $user->paid_emails_left);

        $this->handleEvent($schedule);

        $user->refresh();
        $this->assertSame(0, $user->free_emails_left);
        $this->assertSame(4, $user->paid_emails_left);
    }

    /** @test */
    function it_fires_an_event_when_a_user_is_almost_out_of_emails()
    {
        $this->expectsEvents(UserAlmostOutOfEmails::class);

        [$user, $schedule] = $this->createUserAndSchedule(0, 10);

        $this->handleEvent($schedule);

        Event::assertNotDispatched(UserOutOfEmails::class);
    }

    /** @test */
    function it_fires_an_event_when_a_user_is_out_of_emails()
    {
        $this->expectsEvents(UserOutOfEmails::class);

        [$user, $schedule] = $this->createUserAndSchedule(0, 1);

        $this->handleEvent($schedule);

        Event::assertNotDispatched(UserAlmostOutOfEmails::class);
    }

    /** @test */
    function it_fires_the_events_only_once()
    {
        Event::fake();

        [$user, $schedule] = $this->createUserAndSchedule(0, 9);

        for ($i = 0; $i < 8; $i++) {
            $this->handleEvent($schedule);
        }

        $user->refresh();
        $this->assertSame(0, $user->free_emails_left);
        $this->assertSame(1, $user->paid_emails_left);

        Event::assertNotDispatched(UserAlmostOutOfEmails::class);
        Event::assertNotDispatched(UserOutOfEmails::class);
    }

    private function createUserAndSchedule(int $freeEmails, int $paidEmails)
    {
        $user = factory(User::class)->create([
            'free_emails_left' => $freeEmails,
            'paid_emails_left' => $paidEmails,
        ]);

        $schedule = $user->emailSchedules()->create([
            'what' => 'The what text',
            'when' => 'every month',
        ]);

        return [$user, $schedule];
    }

    private function handleEvent($schedule)
    {
        $listener = new DecrementUserEmailsLeft();

        $email = new class extends Email {
            public function __construct() {}
        };

        $listener->handle(
            new EmailSent($schedule, $email)
        );
    }
}
