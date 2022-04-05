<?php

declare(strict_types = 1);

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;
    protected string $uri;

    protected function setUp(): void
    {
        parent::setUp();

        $this->uri = route('auth.login');
        $this->user = User::factory()->create();
    }

    public function test_user_can_login_from_website(): void
    {
        $data = [
            'email' => $this->user->email,
            'password' => 'password',
            'device_name' => 'web',
        ];

        $response = $this->postJson($this->uri, $data);
        $response->assertOk();

        $this->assertTrue(
            key_exists(
                'Bearer',
                $response->json()
            )
        );
    }

    public function test_user_can_login_from_mobile(): void
    {
        $data = [
            'email' => $this->user->email,
            'password' => 'password',
            'device_name' => 'mobile',
        ];

        $response = $this->postJson($this->uri, $data);
        $response->assertOk();

        $this->assertTrue(
            key_exists(
                'Bearer',
                $response->json()
            )
        );
    }

    public function test_user_can_login_from_desktop(): void
    {
        $data = [
            'email' => $this->user->email,
            'password' => 'password',
            'device_name' => 'desktop',
        ];

        $response = $this->postJson($this->uri, $data);
        $response->assertOk();

        $this->assertTrue(
            key_exists(
                'Bearer',
                $response->json()
            )
        );
    }

    public function test_user_can_not_login_with_invalid_credentails_from_website(): void
    {
        $data = [
            'email' => $this->user->email,
            'password' => 'wrongpassword',
            'device_name' => 'web',
        ];

        $response = $this->postJson($this->uri, $data);
        $response->assertUnprocessable();
    }

    public function test_user_can_not_login_with_invalid_credentails_from_phone(): void
    {
        $data = [
            'email' => $this->user->email,
            'password' => 'wrongpassword',
            'device_name' => 'phone',
        ];

        $response = $this->postJson($this->uri, $data);
        $response->assertUnprocessable();
    }

    public function test_user_can_not_login_with_invalid_credentails_from_desktop(): void
    {
        $data = [
            'email' => $this->user->email,
            'password' => 'wrongpassword',
            'device_name' => 'desktop',
        ];

        $response = $this->postJson($this->uri, $data);
        $response->assertUnprocessable();
    }
}
