<?php

namespace Tests\Unit\Jobs;

use App\Jobs\QueueDueEmailsJob;
use App\Jobs\SendScheduledEmailJob;
use App\Models\EmailSchedule;
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

        $a1 = $amsterdamUserOne->emailSchedules()->create(['what' => 'a1', 'when' => 'in 1 minute']);
        $a2 = $amsterdamUserOne->emailSchedules()->create(['what' => 'a2', 'when' => 'in 1 hour']);

        $b1 = $amsterdamUserTwo->emailSchedules()->create(['what' => 'b1', 'when' => 'in 1 minute']);
        $b2 = $amsterdamUserTwo->emailSchedules()->create(['what' => 'b2', 'when' => 'in 1 hour']);

        QueueDueEmailsJob::dispatchNow();

        Queue::assertNothingPushed();

        $this->progressTimeInMinutes(1);

        QueueDueEmailsJob::dispatchNow();

        $this->assertJobsPushed(2)
            ->assertJobQueued($a1)
            ->assertJobQueued($b1);

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

    private function assertJobQueued(EmailSchedule $emailSchedule)
    {
        Queue::assertPushed(SendScheduledEmailJob::class, function (SendScheduledEmailJob $job) use ($emailSchedule) {
            return $job->emailSchedule->id === $emailSchedule->id;
        });

        return $this;
    }

    private function assertJobsPushed(int $count)
    {
        Queue::assertPushed(SendScheduledEmailJob::class, $count);

        return $this;
    }
}
