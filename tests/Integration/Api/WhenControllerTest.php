<?php

namespace Tests\Integration\Api;

use Tests\TestCase;

class WhenControllerTest extends TestCase
{
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
        ]);

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

    /** @test */
    function default_empty_value_is_valid()
    {
        // "null" is the default value. It is valid, and the human message is empty.
        $this->assertValidHumanInterpretation('', null);
    }
}
