<?php

declare(strict_types = 1);

namespace Tests\Feature\User;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ChangeEmailTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;
    protected string $uri;

    protected function setUp(): void
    {
        parent::setUp();

        $this->uri = route('user.change.email');
        $this->user = User::factory()->create();

        Sanctum::actingAs($this->user);
    }

    public function test_user_can_update_email(): void
    {
        $data = [
            'password' => 'password',
            'email' => 'new@email.com',
        ];

        $this->assertNotSame(
            $data['email'],
            $this->user->email,
        );

        $response = $this->putJson($this->uri, $data);
        $response->assertOk();

        $this->assertSame(
            'Email updated!',
            $response->json()
        );

        $this->user->refresh();

        $this->assertSame(
            $data['email'],
            $this->user->email,
        );
    }

    public function test_empty_request_is_rejected(): void
    {
        $response = $this->putJson($this->uri);
        $response->assertUnprocessable();
    }

    public function test_invalid_request_cant_pass_validation(): void
    {
        $data = [
            'password' => 'password',
            'email' => 'wrong_email',
        ];

        $response = $this->putJson($this->uri, $data);
        $response->assertUnprocessable();

        $data = [
            'password' => 'wrong_password',
            'email' => 'new@email.com',
        ];

        $response = $this->putJson($this->uri, $data);
        $response->assertUnprocessable();
    }
}
