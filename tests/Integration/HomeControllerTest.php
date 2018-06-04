<?php

namespace Tests\Integration;

use App\Models\EmailSchedule;
use App\Models\SiteStats;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HomeControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function an_empty_when_uses_the_users_default_value()
    {
        $this->actingAs(factory(User::class)->create())
            ->postHome([
                'what' => 'Example',
                'when' => '',
            ])
            ->assertStatus(302)
            ->assertRedirect(route('home.success'));

        $this->assertSame('now', EmailSchedule::find(1)->when);
    }

    /** @test */
    function it_keeps_track_of_schedules_created()
    {
        $this->assertSame(0, SiteStats::today()->schedules_created);

        $user = factory(User::class)->create();

        $this->actingAs($user)
            ->postHome(['what' => 'Example', 'when' => 'now'])
            ->assertRedirect(route('home.success'));

        $this->assertSame(1, SiteStats::today()->schedules_created);

        $this->actingAs($user)
            ->postHome(['what' => 'Example', 'when' => 'now'])
            ->assertRedirect(route('home.success'));

        $this->assertSame(2, SiteStats::today()->schedules_created);
    }

    private function postHome($data)
    {
        return $this->post(route('home.post'), $data);
    }
}
