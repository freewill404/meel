<?php

namespace Tests\Unit\Controllers\Api;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HumanInterpretationControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function you_need_to_be_logged_in()
    {
        $this->json('POST', route('api.humanInterpretation'), [
                'when' => 'tomorrow',
            ])
            ->assertStatus(401);
    }

    /** @test */
    function default_empty_value_is_valid()
    {
        // "null" is the default value. It is valid, and the human message is empty.
        $this->apiLogin()->assertValidHumanInterpretation('', null);
    }

    /** @test */
    function basic_invalid_interpretation()
    {
        $this->apiLogin()->assertInvalidHumanInterpretation('', 'ALL THE TIME BRO!');
    }

    /** @test */
    function basic_non_recurring_interpretation()
    {
        $this->apiLogin()->assertValidHumanInterpretation('Once, at 2018-04-04 16:00:00 (Wednesday)', 'next week at 16:00');
    }

    private function assertValidHumanInterpretation($expected, $input)
    {
        return $this->assertHumanInterpretation($expected, $input, true);
    }

    private function assertInvalidHumanInterpretation($expected, $input)
    {
        return $this->assertHumanInterpretation($expected, $input, false);
    }

    private function assertHumanInterpretation($expected, $input, $expectedIsValid)
    {
        $content = $this->json('POST', route('api.humanInterpretation'), [
                'when' => $input,
            ])
            ->assertStatus(200)
            ->getContent();

        $data = json_decode($content);

        $this->assertSame(
            $expectedIsValid,
            $data->valid,
            $expectedIsValid ? "Interpretation should be valid, was invalid: {$input}" : "Interpretation should be invalid, was valid: {$input}"
        );

        $this->assertSame($expected, $data->humanInterpretation);
    }
}
