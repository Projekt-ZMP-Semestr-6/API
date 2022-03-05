<?php

declare(strict_types = 1);

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\PersonalAccessToken;
use Tests\TestCase;

class LogoutTest extends TestCase
{
    use RefreshDatabase;

    protected PersonalAccessToken $token;
    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->createOne();

    }

    public function test_user_can_logout_from_website(): void
    {
        $tokenWeb = $this->loginUser('web');

        $response = $this->getJson('/api/auth/logout', ['Authorization' => "Bearer {$tokenWeb}"]);
        $response->assertOk();
    }

    public function test_user_can_logout_from_phone(): void
    {
        $tokenWeb = $this->loginUser('phone');

        $response = $this->getJson('/api/auth/logout', ['Authorization' => "Bearer {$tokenWeb}"]);
        $response->assertOk();
    }

    public function test_user_can_logout_from_desktop(): void
    {
        $tokenWeb = $this->loginUser('desktop');

        $response = $this->getJson('/api/auth/logout', ['Authorization' => "Bearer {$tokenWeb}"]);
        $response->assertOk();
    }

    private function loginUser(string $deviceName): string
    {
        $data = [
            'email' => $this->user->email,
            'password' => 'password',
            'device_name' => $deviceName,
        ];

        $response = $this->postJson('/api/auth/login', $data);
        $response->assertOk();

        return $response->json('Bearer');
    }
}
