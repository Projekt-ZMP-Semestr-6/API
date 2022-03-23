<?php

declare(strict_types = 1);

namespace Tests\Feature\User;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class DeleteAccountTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;
    protected string $uri;

    protected function setUp(): void
    {
        parent::setUp();

        $this->uri = route('user.delete');
        $this->user = User::factory()->create();

        $this->assertModelExists($this->user);

        Sanctum::actingAs($this->user);
    }

    public function test_user_can_delete_password(): void
    {
        $data = [
            'password' => 'password'
        ];

        $response = $this->deleteJson($this->uri, $data);
        $response->assertOk();

        $this->assertSame('Account deleted!', $response->json());
        $this->assertModelMissing($this->user);
    }

    public function test_empty_request_is_rejected(): void
    {
        $response = $this->deleteJson($this->uri);
        $response->assertUnprocessable();

        $this->assertModelExists($this->user);
    }

    public function test_invalid_request_cant_pass_validation(): void
    {
        $data = [
            'password' => 'wrong_password',
        ];

        $response = $this->deleteJson($this->uri, $data);
        $response->assertUnprocessable();

        $this->assertModelExists($this->user);
    }
}
