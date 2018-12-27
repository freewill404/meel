<?php

namespace Tests\Unit;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PagesTest extends TestCase
{
    use RefreshDatabase;

    protected $guestRoutes = [
        'help',
        'home',
        'login',
        'register',
        'register.done',
        'password.request',
        'password.requested',
    ];

    protected $userRoutes = [
        'user.schedules',
        'user.schedules.ended',
        'user.schedules.ok',
        'user.feeds',
        'user.feeds.create',
        'user.feedback',
        'user.feedback.done',
        'user.account',
        'user.more',
    ];

    /** @test */
    function guest_pages_work()
    {
        foreach ($this->guestRoutes as $route) {
            $url = route($route);

            $response = $this->get($url);

            $this->assertSame(200, $response->getStatusCode(), "'{$url}' seems to not work!");
        }
    }

    /** @test */
    function user_pages_work()
    {
        $user = factory(User::class)->create();

        foreach ($this->userRoutes as $route) {
            $url = route($route);

            $response = $this->actingAs($user)->get($url);

            $this->assertSame(200, $response->getStatusCode(), "'{$url}' seems to not work!");
        }
    }
}
