<?php

namespace Tests\Unit\Controllers\Api;

use App\Models\EmailSchedule;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EmailScheduleControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function it_can_update_a_schedule()
    {
        [$user, $schedule] = $this->createUserAndSchedule();

        $this->apiLogin($user)
            ->updateSchedule($schedule, 'updated!')
            ->assertStatus(200);

        $this->assertSame('updated!', $schedule->refresh()->what);
    }

    /** @test */
    function it_wont_update_an_ended_schedule()
    {
        [$user, $schedule] = $this->createUserAndSchedule();

        $schedule->sendEmail();

        $this->assertNull($schedule->refresh()->next_occurrence);

        $this->apiLogin($user)
            ->updateSchedule($schedule, 'updated!')
            ->assertStatus(403);

        $this->assertSame('the what text', $schedule->refresh()->what);
    }

    /** @test */
    function it_only_updates_schedules_you_own()
    {
        [$user1, $schedule1] = $this->createUserAndSchedule();
        [$user2, $schedule2] = $this->createUserAndSchedule();

        $this->apiLogin($user1)
            ->updateSchedule($schedule2, 'updated!')
            ->assertStatus(403);

        $this->assertSame('the what text', $schedule2->refresh()->what);
    }

    /** @test */
    function it_can_delete_a_schedule()
    {
        [$user, $schedule] = $this->createUserAndSchedule();

        $this->apiLogin($user)
            ->deleteSchedule($schedule)
            ->assertStatus(200);

        $this->assertSame(0, EmailSchedule::count());
    }

    /** @test */
    function it_only_deletes_schedules_you_own()
    {
        [$user1, $schedule1] = $this->createUserAndSchedule();
        [$user2, $schedule2] = $this->createUserAndSchedule();

        $this->apiLogin($user1)
            ->deleteSchedule($schedule2)
            ->assertStatus(403);

        $this->assertSame(2, EmailSchedule::count());
    }

    private function createUserAndSchedule()
    {
        $user = factory(User::class)->create();

        $emailSchedule = $user->emailSchedules()->create([
            'what' => 'the what text',
            'when' => 'tomorrow',
        ]);

        return [$user, $emailSchedule];
    }

    private function updateSchedule($emailSchedule, $what)
    {
        return $this->json('PUT', route('api.emailSchedule.put', $emailSchedule), ['what' => $what]);
    }

    private function deleteSchedule($schedule)
    {
        return $this->json('DELETE', route('api.emailSchedule.delete', $schedule));
    }
}
