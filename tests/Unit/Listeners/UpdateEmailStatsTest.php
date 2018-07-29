<?php

namespace Tests\Unit\Listeners;

use App\Events\EmailSent;
use App\Events\UserAlmostOutOfEmails;
use App\Events\UserOutOfEmails;
use App\Listeners\UpdateEmailStats;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Mail\Mailable;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class UpdateEmailStatsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function it_decrements_emails_left()
    {
        [$user, $schedule] = $this->createUserAndSchedule(100);

        $this->assertSame(100, $user->emails_left);

        (new UpdateEmailStats)->handle(
            new EmailSent($user, new Mailable)
        );

        $this->assertSame(99, $user->refresh()->emails_left);
    }

    /** @test */
    function it_fires_an_event_when_a_user_is_almost_out_of_emails()
    {
        $this->expectsEvents(UserAlmostOutOfEmails::class);

        [$user, $schedule] = $this->createUserAndSchedule(10);

        (new UpdateEmailStats)->handle(
            new EmailSent($user, new Mailable)
        );

        Event::assertNotDispatched(UserOutOfEmails::class);
    }

    /** @test */
    function it_fires_an_event_when_a_user_is_out_of_emails()
    {
        $this->expectsEvents(UserOutOfEmails::class);

        [$user, $schedule] = $this->createUserAndSchedule(1);

        (new UpdateEmailStats)->handle(
            new EmailSent($user, new Mailable)
        );

        Event::assertNotDispatched(UserAlmostOutOfEmails::class);
    }

    /** @test */
    function it_fires_the_events_only_once()
    {
        Event::fake();

        [$user, $schedule] = $this->createUserAndSchedule(9);

        for ($i = 0; $i < 8; $i++) {
            (new UpdateEmailStats)->handle(
                new EmailSent($user, new Mailable)
            );
        }

        $user->refresh();
        $this->assertSame(1, $user->emails_left);

        Event::assertNotDispatched(UserAlmostOutOfEmails::class);
        Event::assertNotDispatched(UserOutOfEmails::class);
    }

    private function createUserAndSchedule(int $emailsLeft)
    {
        $user = factory(User::class)->create([
            'emails_left' => $emailsLeft,
        ]);

        $schedule = $user->schedules()->create([
            'what' => 'The what text',
            'when' => 'every month',
        ]);

        return [$user, $schedule];
    }
}
