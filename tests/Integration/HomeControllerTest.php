<?php

namespace Tests\Integration;

use App\Models\EmailSchedule;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HomeControllerTest extends TestCase
{
    use RefreshDatabase;

    private function postHome($data)
    {
        return $this->post(route('home.post'), $data);
    }

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
}
