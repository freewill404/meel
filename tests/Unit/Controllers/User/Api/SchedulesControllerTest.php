<?php

namespace Tests\Unit\Controllers\User\Api;

use App\Models\Schedule;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SchedulesControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function it_can_show_all_upcoming_schedules()
    {
        $user = factory(User::class)->create();

        $schedule1 = $user->schedules()->create([
            'what' => 'aaa',
            'when' => 'tomorrow at 12:00',
        ]);

        $schedule2 = $user->schedules()->create([
            'what' => 'bbb',
            'when' => 'in 1 hours',
        ]);

        $schedule3 = $user->schedules()->create([
            'what' => 'ccc',
            'when' => 'in 3 hours',
        ]);

        $this->progressTimeInHours(2);

        $schedule2->update(['next_occurrence' => null]);

        $this->apiLogin($user)
            ->getUpcomingSchedules()
            ->assertStatus(200)
            ->assertSeeInOrder(['ccc', 'aaa'])
            ->assertDontSee('bbb');
    }

    /** @test */
    function it_shows_upcoming_schedules_in_the_users_timezone()
    {
        Carbon::setTestNow('2018-12-15 12:00:15');

        $user = factory(User::class)->create(['timezone' => 'Asia/Shanghai']);

        $schedule1 = $user->schedules()->create([
            'what' => 'aaa',
            'when' => 'daily at 20:30',
            'last_sent_at' => '2018-12-14 13:30:00'
        ]);

        // stored in server time
        $this->assertSame('2018-12-15 13:30:00', (string) $schedule1->next_occurrence);
        $this->assertSame('2018-12-14 13:30:00', (string) $schedule1->last_sent_at);

        $this->apiLogin($user)
            ->getUpcomingSchedules()
            ->assertStatus(200)
            // displayed in the user's timezone
            ->assertSee('2018-12-15 20:30:00')
            ->assertSee('2018-12-14 20:30:00')
            ->assertDontSee('2018-12-15 13:30')
            ->assertDontSee('2018-12-14 13:30');
    }

    /** @test */
    function it_can_show_all_ended_schedules()
    {
        $user = factory(User::class)->create();

        $schedule1 = $user->schedules()->create([
            'what' => 'aaa',
            'when' => 'tomorrow at 12:00',
        ]);

        $schedule2 = $user->schedules()->create([
            'what' => 'bbb',
            'when' => 'in 1 hours',
        ]);

        $schedule3 = $user->schedules()->create([
            'what' => 'ccc',
            'when' => 'in 3 hours',
        ]);

        $this->progressTimeInHours(4);

        $schedule2->update(['next_occurrence' => null, 'last_sent_at' => now()->subHours(3)]);
        $schedule3->update(['next_occurrence' => null, 'last_sent_at' => now()->subHours(1)]);

        $this->apiLogin($user)
            ->getEndedSchedules()
            ->assertStatus(200)
            ->assertSeeInOrder(['bbb', 'ccc'])
            ->assertDontSee('aaa');
    }

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

        $this->assertSame(0, Schedule::count());
    }

    /** @test */
    function it_only_deletes_schedules_you_own()
    {
        [$user1, $schedule1] = $this->createUserAndSchedule();
        [$user2, $schedule2] = $this->createUserAndSchedule();

        $this->apiLogin($user1)
            ->deleteSchedule($schedule2)
            ->assertStatus(403);

        $this->assertSame(2, Schedule::count());
    }

    private function createUserAndSchedule()
    {
        $user = factory(User::class)->create();

        $schedule = $user->schedules()->create([
            'what' => 'the what text',
            'when' => 'tomorrow',
        ]);

        return [$user, $schedule];
    }

    private function updateSchedule($schedule, $what)
    {
        return $this->json('PUT', route('api.schedule.put', $schedule), ['what' => $what]);
    }

    private function getUpcomingSchedules()
    {
        return $this->json('GET', route('api.schedules.upcoming'));
    }

    private function getEndedSchedules()
    {
        return $this->json('GET', route('api.schedules.ended'));
    }

    private function deleteSchedule($schedule)
    {
        return $this->json('DELETE', route('api.schedule.delete', $schedule));
    }
}
