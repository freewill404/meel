<?php

namespace Tests\Integration\Api;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WhenControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function default_empty_value_is_valid()
    {
        $this->actingAs(
            factory(User::class)->create(), 'api'
        );

        // "null" is the default value. It is valid, and the human message is empty.
        $this->assertValidHumanInterpretation('', null);
    }

    /** @test */
    function basic_invalid_interpretation()
    {
        $this->actingAs(
            factory(User::class)->create(), 'api'
        );

        $this->assertInvalidHumanInterpretation('', 'ALL THE TIME BRO!');
    }

    /** @test */
    function basic_non_recurring_interpretation()
    {
        $this->actingAs(
            factory(User::class)->create(), 'api'
        );

        $this->assertValidHumanInterpretation('Once, at 2018-04-04 16:00:00 (Wednesday)', 'next week at 16:00');
    }

    private function assertValidHumanInterpretation($expected, $input)
    {
        return $this->assertHumanInterpretation($expected, $input, true);
    }

    private function assertInvalidHumanInterpretation($expected, $input)
    {
        return $this->assertHumanInterpretation($expected, $input, false);
    }

    private function assertHumanInterpretation($expected, $input, $isValid)
    {
        $response = $this->post(route('api.humanInterpretation'), [
            'when' => $input,
        ])
            ->assertStatus(200);

        $content = (array) json_decode($response->getContent());
        $this->assertSame(
            $isValid,
            $content['valid'],
            $isValid ? "Interpretation should be valid, was invalid: {$input}" : "Interpretation should be invalid, was valid: {$input}"
        );

        $this->assertSame(
            $expected,
            $content['humanInterpretation']
        );

        return $response;
    }
}
