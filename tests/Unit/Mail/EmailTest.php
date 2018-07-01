<?php

namespace Tests\Unit\Mail;

use App\Mail\Email;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WhatStringTest extends TestCase
{
    use RefreshDatabase;

    protected $mailFake = false;

    /** @test */
    function it_uses_the_formatted_what_string()
    {
        $user = factory(User::class)->create();

        $emailSchedule = $user->emailSchedules()->create([
            'what' => 'times sent: %t',
            'when' => 'in 1 minute',
        ]);

        $email = new Email($emailSchedule);

        $view = $email->render();

        $this->assertSame('times sent: 1', $email->subject);

        $this->assertContains('times sent: 1', $view);

        $this->assertNotContains('%t', $view);
    }
}
