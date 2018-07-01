<?php

namespace Tests\Unit\Models;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function it_can_get_all_ids_per_timezone()
    {
        factory(User::class, 3)->create(['timezone' => 'Europe/Amsterdam']);
        factory(User::class, 1)->create(['timezone' => 'UTC']);
        factory(User::class, 3)->create(['timezone' => 'Europe/London']);
        factory(User::class, 3)->create(['timezone' => 'Asia/Shanghai']);

        $userIdsByTimezone = User::getIdsByTimezone();

        $this->assertSame([
            'Asia/Shanghai'    => [0 => 8, 1 => 9, 2 => 10],
            'Europe/Amsterdam' => [0 => 1, 1 => 2, 2 => 3],
            'Europe/London'    => [0 => 5, 1 => 6, 2 => 7],
            'UTC'              => [0 => 4],
        ], $userIdsByTimezone);
    }
}
