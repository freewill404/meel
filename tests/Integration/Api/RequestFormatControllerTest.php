<?php

namespace Tests\Integration\Api;

use App\Models\FormatRequest;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RequestFormatControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function you_have_to_be_logged_in()
    {
        $this->postRequestFormat('aaaaa')
            ->assertStatus(401);
    }

    /** @test */
    function you_can_request_a_when_format()
    {
        $this->apiLogin()
            ->postRequestFormat('aaaaa')
            ->assertStatus(200);

        $formatRequest = FormatRequest::findOrFail(1);

        $this->assertSame('aaaaa', $formatRequest->format);
    }

    private function postRequestFormat(string $when)
    {
        return $this->json('POST', route('api.requestWhenFormat'), ['when' => $when]);
    }
}
