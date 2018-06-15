<?php

namespace Tests\Integration;

use App\Models\Feedback;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FeedbackControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function you_need_to_be_logged_in()
    {
        $this->get(route('feedback'))
            ->assertStatus(302);

        $user = factory(User::class)->create();

        $this->actingAs($user)
            ->get(route('feedback'))
            ->assertStatus(200);
    }

    /** @test */
    function you_can_request_a_format()
    {
        $user = factory(User::class)->create();

        $this->actingAs($user)
            ->post(route('feedback'), [
                'feedback' => 'FEEDBACK',
            ])
            ->assertRedirect(route('feedback.done'));

        $feedback = Feedback::find(1);

        $this->assertSame('FEEDBACK', $feedback->feedback);

        $this->assertSame($user->id, $feedback->user_id);
    }
}
