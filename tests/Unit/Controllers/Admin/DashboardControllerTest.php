<?php

namespace Tests\Unit\Controllers\Admin;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function it_can_show_the_dashboard()
    {
        $this->adminLogin()
            ->getDashboard()
            ->assertStatus(200);
    }

    /** @test */
    function you_have_to_be_an_admin()
    {
        $this->login()
            ->getDashboard()
            ->assertStatus(403);
    }

    private function getDashboard()
    {
        return $this->get(route('admin.dashboard'));
    }
}
