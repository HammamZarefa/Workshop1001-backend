<?php

namespace Tests\Feature\Errors;

use Tests\TestCase;

class ValidationExceptionTest extends TestCase
{
    public function test_validation()
    {
        $response = $this->postJson('/api/v1/login', [
            'email' => '', 
            'password' => ''
        ]);

        $response->assertStatus(422)
                 ->assertJson([
                     'success' => false,
                     'message' => 'Validation error',
                 ])
                 ->assertJsonStructure([
                     'success',
                     'message',
                     'errors' => ['email', 'password']
                 ]);
    }
}
