<?php

namespace Tests\Integration;

use App\Models\User;
use App\Support\Enums\UserRole;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Route;
use Tests\TestCase;

class IsAdminTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function it_only_allows_admins()
    {
        Route::middleware('is-admin')->get('/test-route', function () {

        });

        $normalUser = factory(User::class)->create([
            'role' => UserRole::USER,
        ]);

        $adminUser = factory(User::class)->create([
            'role' => UserRole::ADMIN,
        ]);

        $this->actingAs($normalUser)
            ->get('/test-route')
            ->assertStatus(401);

        $this->actingAs($adminUser)
            ->get('/test-route')
            ->assertStatus(200);
    }

    /** @test */
    function it_only_allows_if_logged_in()
    {
        Route::middleware('is-admin')->get('/test-route', function () {

        });

        $this->get('/test-route')
            ->assertStatus(401);
    }
}
