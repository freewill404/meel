<?php

namespace Tests\Unit\Jobs;

use App\Jobs\QueueDueEmailsJob;
use App\Jobs\SendScheduledEmailJob;
use App\Models\Schedule;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class QueueDueEmailsJobTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function it_queues_due_emails()
    {
        Queue::fake();

        $amsterdamUserOne = factory(User::class)->create(['timezone' => 'Europe/Amsterdam']);
        $amsterdamUserTwo = factory(User::class)->create(['timezone' => 'Europe/Amsterdam']);

        $a1 = $amsterdamUserOne->schedules()->create(['what' => 'a1', 'when' => 'in 1 minute']);
        $a2 = $amsterdamUserOne->schedules()->create(['what' => 'a2', 'when' => 'in 1 hour']);

        $b1 = $amsterdamUserTwo->schedules()->create(['what' => 'b1', 'when' => 'in 1 minute']);
        $b2 = $amsterdamUserTwo->schedules()->create(['what' => 'b2', 'when' => 'in 1 hour']);

        QueueDueEmailsJob::dispatchNow();

        Queue::assertNothingPushed();

        $this->progressTimeInMinutes(1);

        $this->assertSame('2018-03-28 12:01:00', (string) secondless_now());
        $this->assertSame('2018-03-28 12:01:00', (string) $a1->next_occurrence);

        QueueDueEmailsJob::dispatchNow();

        $this->assertJobsPushed(2)
            ->assertJobQueued($a1)
            ->assertJobQueued($b1);

        // Next occurrence is set to null when they are sent.
        $a1->update(['next_occurrence' => null]);
        $b1->update(['next_occurrence' => null]);

        $this->progressTimeInMinutes(29);

        QueueDueEmailsJob::dispatchNow();

        // Nothing else should be pushed
        $this->assertJobsPushed(2);

        $this->progressTimeInMinutes(30);

        QueueDueEmailsJob::dispatchNow();

        $this->assertJobsPushed(4)
            ->assertJobQueued($a2)
            ->assertJobQueued($b2);
    }

    /** @test */
    function it_queues_schedules_that_should_have_already_occurred()
    {
        Queue::fake();

        $user = factory(User::class)->create();

        $schedule = $user->schedules()->create(['what' => 'text', 'when' => 'in 1 minute']);

        QueueDueEmailsJob::dispatchNow();

        // The schedule now has a "next_occurrence" in the past.
        $this->progressTimeInMinutes(5);

        $this->assertSame('2018-03-28 12:05:00', (string) secondless_now());
        $this->assertSame('2018-03-28 12:01:00', (string) $schedule->next_occurrence);

        QueueDueEmailsJob::dispatchNow();

        $this->assertJobsPushed(1)
            ->assertJobQueued($schedule);
    }

    private function assertJobQueued(Schedule $schedule)
    {
        Queue::assertPushed(SendScheduledEmailJob::class, function (SendScheduledEmailJob $job) use ($schedule) {
            return $job->schedule->id === $schedule->id;
        });

        return $this;
    }

    private function assertJobsPushed(int $count)
    {
        Queue::assertPushed(SendScheduledEmailJob::class, $count);

        return $this;
    }
}
