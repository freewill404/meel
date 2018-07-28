<?php

namespace Tests\Unit\Jobs;

use App\Jobs\QueueDueEmailsJob;
use App\Mail\Email;
use App\Models\Schedule;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class QueueDueEmailsJobTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function it_queues_due_emails()
    {
        $amsterdamUserOne = factory(User::class)->create(['timezone' => 'Europe/Amsterdam']);
        $amsterdamUserTwo = factory(User::class)->create(['timezone' => 'Europe/Amsterdam']);

        $a1 = $amsterdamUserOne->schedules()->create(['what' => 'a1', 'when' => 'in 1 minute']);
        $a2 = $amsterdamUserOne->schedules()->create(['what' => 'a2', 'when' => 'in 1 hour']);

        $b1 = $amsterdamUserTwo->schedules()->create(['what' => 'b1', 'when' => 'in 1 minute']);
        $b2 = $amsterdamUserTwo->schedules()->create(['what' => 'b2', 'when' => 'in 1 hour']);

        (new QueueDueEmailsJob)->handle();

        Mail::assertNothingQueued();

        $this->progressTimeInMinutes(1);

        $this->assertSame('2018-03-28 12:01:00', (string) secondless_now());
        $this->assertSame('2018-03-28 12:01:00', (string) $a1->next_occurrence);

        (new QueueDueEmailsJob)->handle();

        Mail::assertQueued(Email::class, 2);

        $this->assertEmailQueued($a1)
            ->assertEmailQueued($b1);

        // Next occurrence is set to null when they are sent.
        $a1->update(['next_occurrence' => null]);
        $b1->update(['next_occurrence' => null]);

        $this->progressTimeInMinutes(29);

        (new QueueDueEmailsJob)->handle();

        // Nothing else should be pushed
        Mail::assertQueued(Email::class, 2);

        $this->progressTimeInMinutes(30);

        (new QueueDueEmailsJob)->handle();

        Mail::assertQueued(Email::class, 4);

        $this->assertEmailQueued($a2)
            ->assertEmailQueued($b2);
    }

    /** @test */
    function it_queues_schedules_that_should_have_already_occurred()
    {
        $user = factory(User::class)->create();

        $schedule = $user->schedules()->create(['what' => 'text', 'when' => 'in 1 minute']);

        (new QueueDueEmailsJob)->handle();

        // The schedule now has a "next_occurrence" in the past.
        $this->progressTimeInMinutes(5);

        $this->assertSame('2018-03-28 12:05:00', (string) secondless_now());
        $this->assertSame('2018-03-28 12:01:00', (string) $schedule->next_occurrence);

        (new QueueDueEmailsJob)->handle();

        Mail::assertQueued(Email::class, 1);

        $this->assertEmailQueued($schedule);
    }

    private function assertEmailQueued(Schedule $schedule)
    {
        Mail::assertQueued(Email::class, function (Email $email) use ($schedule) {
            return $email->schedule->id === $schedule->id;
        });

        return $this;
    }
}
