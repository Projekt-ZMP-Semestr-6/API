<?php

declare(strict_types = 1);

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->createOne();
    }

    public function test_user_can_login_from_website(): void
    {
        $data = [
            'email' => $this->user->email,
            'password' => 'password',
            'device_name' => 'web',
        ];

        $response = $this->postJson(route('auth.login'), $data);
        $response->assertOk();

        $this->assertTrue(key_exists('Bearer', $response->json()));
    }

    public function test_user_can_login_from_phone(): void
    {
        $data = [
            'email' => $this->user->email,
            'password' => 'password',
            'device_name' => 'phone',
        ];

        $response = $this->postJson(route('auth.login'), $data);
        $response->assertOk();

        $this->assertTrue(key_exists('Bearer', $response->json()));
    }

    public function test_user_can_login_from_desktop(): void
    {
        $data = [
            'email' => $this->user->email,
            'password' => 'password',
            'device_name' => 'desktop',
        ];

        $response = $this->postJson(route('auth.login'), $data);
        $response->assertOk();

        $this->assertTrue(key_exists('Bearer', $response->json()));
    }

    public function test_user_can_not_login_with_invalid_credentails_from_website(): void
    {
        $data = [
            'email' => $this->user->email,
            'password' => 'wrongpassword',
            'device_name' => 'web',
        ];

        $response = $this->postJson(route('auth.login'), $data);
        $response->assertUnprocessable();
    }

    public function test_user_can_not_login_with_invalid_credentails_from_phone(): void
    {
        $data = [
            'email' => $this->user->email,
            'password' => 'wrongpassword',
            'device_name' => 'phone',
        ];

        $response = $this->postJson(route('auth.login'), $data);
        $response->assertUnprocessable();
    }

    public function test_user_can_not_login_with_invalid_credentails_from_desktop(): void
    {
        $data = [
            'email' => $this->user->email,
            'password' => 'wrongpassword',
            'device_name' => 'desktop',
        ];

        $response = $this->postJson(route('auth.login'), $data);
        $response->assertUnprocessable();
    }
}
