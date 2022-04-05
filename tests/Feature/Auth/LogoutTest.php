<?php

declare(strict_types = 1);

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\PersonalAccessToken;
use Tests\TestCase;

class LogoutTest extends TestCase
{
    use RefreshDatabase;

    protected PersonalAccessToken $token;
    protected User $user;
    protected string $uri;

    protected function setUp(): void
    {
        parent::setUp();

        $this->uri = route('auth.logout');
        $this->user = User::factory()->createOne();
    }

    public function test_user_can_logout_from_website(): void
    {
        $tokenWeb = $this->loginUser('web');

        $data = [
            'Authorization' => "Bearer {$tokenWeb}"
        ];

        $response = $this->getJson($this->uri, $data);
        $response->assertOk();
    }

    public function test_user_can_logout_from_mobile(): void
    {
        $tokenWeb = $this->loginUser('mobile');

        $data = [
            'Authorization' => "Bearer {$tokenWeb}"
        ];

        $response = $this->getJson($this->uri, $data);
        $response->assertOk();
    }

    public function test_user_can_logout_from_desktop(): void
    {
        $tokenWeb = $this->loginUser('desktop');

        $data = [
            'Authorization' => "Bearer {$tokenWeb}"
        ];

        $response = $this->getJson($this->uri, $data);
        $response->assertOk();
    }

    private function loginUser(string $deviceName): string
    {
        $data = [
            'email' => $this->user->email,
            'password' => 'password',
            'device_name' => $deviceName,
        ];

        $response = $this->postJson(
            route('auth.login'),
            $data
        );

        $response->assertOk();

        return $response->json('Bearer');
    }
}
