<?php

namespace Tests\Unit\Controllers\Admin;

use App\Models\InputLog;
use Illuminate\Foundation\Testing\RefreshDatabase;
use InputLogsTableSeeder;
use Tests\TestCase;

class InputLogsControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function it_can_show_the_page()
    {
        $this->seed(InputLogsTableSeeder::class);

        $this->adminLogin()
            ->getIndex()
            ->assertStatus(200);
    }

    /** @test */
    function it_can_delete_an_input_log()
    {
        $this->seed(InputLogsTableSeeder::class);

        $originalIds = InputLog::all()->pluck('session_id')->unique();

        $this->adminLogin()
            ->deleteInputLog($deletingSessionId = $originalIds->first())
            ->assertSessionHasNoErrors()
            ->assertStatus(302);

        $currentIds = InputLog::all()->pluck('session_id')->unique();

        $this->assertFalse(
            $currentIds->contains($deletingSessionId)
        );

        $this->assertTrue(
            ($originalIds->count() - 1) === $currentIds->count()
        );
    }

    private function getIndex()
    {
        return $this->get(route('admin.inputLogs.index'));
    }

    private function deleteInputLog($sessionId)
    {
        return $this->delete(route('admin.inputLogs.delete'), [
            'session_id' => $sessionId,
        ]);
    }
}
