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
        $firstUser = factory(User::class)->create();

        $this->assertSame(0, $firstUser->emails_sent);

        $this->assertSame(0, SiteStats::today()->schedules_created);

        $this->actingAs($firstUser)
            ->postHome(['what' => 'Example', 'when' => 'now'])
            ->assertRedirect(route('home.success'));

        $this->assertSame(1, SiteStats::today()->schedules_created);

        $this->assertSame(1, $firstUser->refresh()->schedules_created);

        $secondUser = factory(User::class)->create();

        $this->actingAs($secondUser)
            ->postHome(['what' => 'Example', 'when' => 'now'])
            ->assertRedirect(route('home.success'));

        $this->assertSame(2, SiteStats::today()->schedules_created);

        $this->assertSame(1, $secondUser->refresh()->schedules_created);
    }

    private function postHome($data)
    {
        return $this->post(route('home.post'), $data);
    }
}
