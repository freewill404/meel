<?php

namespace Tests\Integration;

use App\Models\FormatRequest;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RequestFormatControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function you_need_to_be_logged_in()
    {
        $this->get(route('user.requestFormat'))
            ->assertStatus(302);

        $user = factory(User::class)->create();

        $this->actingAs($user)
            ->get(route('user.requestFormat'))
            ->assertStatus(200);
    }

    /** @test */
    function you_can_request_a_format()
    {
        $user = factory(User::class)->create();

        $this->actingAs($user)
            ->post(route('user.requestFormat'), [
                'format' => 'REQUEST',
            ])
            ->assertRedirect(route('user.requestFormat.done'));

        $formatRequest = FormatRequest::find(1);

        $this->assertSame('REQUEST', $formatRequest->format);

        $this->assertSame($user->id, $formatRequest->user_id);
    }
}
