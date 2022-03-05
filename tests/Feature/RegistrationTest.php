<?php

declare(strict_types = 1);

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function test_user_can_register(): void
    {
        $fakePassword = $this->faker->password(8);
        $data = [
            'name' => $this->faker->name(),
            'email' => $this->faker->email(),
            'password' => $fakePassword,
            'password_confirmation' => $fakePassword,
        ];

        $response = $this->postJson('/api/auth/register', $data);
        $response->assertCreated();

        $createdUser = $response->json();
        $this->assertDatabaseHas('users',  $createdUser);
    }

    public function test_user_can_not_register_with_invalid_name(): void
    {
        $fakePassword = $this->faker->password(8);
        $data = [
            'name' => 'bob',
            'email' => $this->faker->name(),
            'password' => $fakePassword,
        ];

        $response = $this->postJson('/api/auth/register', $data);
        $response->assertUnprocessable();
    }

    public function test_user_can_not_register_with_invalid_email(): void
    {
        $fakePassword = $this->faker->password(8);
        $data = [
            'name' => $this->faker->name(),
            'email' => $this->faker->name(),
            'password' => $fakePassword,
            'password_confirmation' => $fakePassword,
        ];

        $response = $this->postJson('/api/auth/register', $data);
        $response->assertUnprocessable();
    }

    public function test_user_can_not_register_with_invalid_password(): void
    {
        $fakePassword = $this->faker->password(4,7);
        $data = [
            'name' => $this->faker->name(),
            'email' => $this->faker->email(),
            'password' => $fakePassword,
            'password_confirmation' => $fakePassword,
        ];

        $response = $this->postJson('/api/auth/register', $data);
        $response->assertUnprocessable();


    }
}
