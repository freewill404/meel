<?php

namespace Tests\Unit\Controllers;

use App\Models\Schedule;
use App\Models\SiteStats;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SchedulesControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function an_empty_when_uses_the_users_default_value()
    {
        $this->actingAs(factory(User::class)->create())
            ->createSchedule([
                'what' => 'Example',
                'when' => '',
            ])
            ->assertStatus(302)
            ->assertRedirect(route('user.schedules.ok'));

        $this->assertSame('now', Schedule::find(1)->when);
    }

    /** @test */
    function it_keeps_track_of_email_schedules_created()
    {
        $firstUser = factory(User::class)->create();

        $this->assertSame(0, $firstUser->email_schedules_created);

        $this->assertSame(0, SiteStats::today()->email_schedules_created);

        $this->actingAs($firstUser)
            ->createSchedule(['what' => 'Example', 'when' => 'now'])
            ->assertRedirect(route('user.schedules.ok'));

        $this->assertSame(1, SiteStats::today()->email_schedules_created);

        $this->assertSame(1, $firstUser->refresh()->email_schedules_created);

        $secondUser = factory(User::class)->create();

        $this->actingAs($secondUser)
            ->createSchedule(['what' => 'Example', 'when' => 'now'])
            ->assertRedirect(route('user.schedules.ok'));

        $this->assertSame(2, SiteStats::today()->email_schedules_created);

        $this->assertSame(1, $secondUser->refresh()->email_schedules_created);
    }

    private function createSchedule($data)
    {
        return $this->post(route('user.schedules.post'), $data);
    }
}
