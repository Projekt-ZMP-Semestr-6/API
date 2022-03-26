<?php

declare(strict_types = 1);

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected string $uri;

    protected function setUp(): void
    {
        parent::setUp();

        $this->uri = route('auth.register');
    }

    public function test_user_can_register(): void
    {
        $data = [
            'name' => 'bob4',
            'email' => 'test@test.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ];

        $response = $this->postJson($this->uri, $data);
        $response->assertCreated();

        $this->assertDatabaseHas(
            'users',
            $response->json()
        );
    }

    public function test_user_can_not_register_with_invalid_name(): void
    {
        $data = [
            'name' => 'bob',
            'email' => 'test@test.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ];

        $response = $this->postJson($this->uri, $data);
        $response->assertUnprocessable();
    }

    public function test_user_can_not_register_with_invalid_email(): void
    {
        $data = [
            'name' => 'bob4',
            'email' => 'invalid_email',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ];

        $response = $this->postJson($this->uri, $data);
        $response->assertUnprocessable();
    }

    public function test_user_can_not_register_with_invalid_password(): void
    {
        $data = [
            'name' => 'bob4',
            'email' => 'test@test.com',
            'password' => 'invalid',
            'password_confirmation' => 'invalid',
        ];

        $response = $this->postJson($this->uri, $data);
        $response->assertUnprocessable();
    }
}
