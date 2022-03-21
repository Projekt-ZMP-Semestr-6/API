<?php

declare(strict_types = 1);

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class DeleteAccountTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->createOne();
        $this->assertDatabaseHas('users', ['id' => $this->user->id]);

        Sanctum::actingAs($this->user);
    }

    public function test_user_can_delete_password(): void
    {
        $data = [
            'password' => 'password'
        ];

        $response = $this->postJson(route('user.delete'), $data);
        $response->assertOk();

        $this->assertSame('Account deleted!', $response->json());
        $this->assertDatabaseMissing('users', ['id' => $this->user->id]);
    }

    public function test_empty_request_is_rejected(): void
    {
        $response = $this->postJson(route('user.delete'));
        $response->assertUnprocessable();

        $this->assertDatabaseHas('users', ['id' => $this->user->id]);
    }

    public function test_invalid_request_cant_pass_validation(): void
    {
        $data = [
            'password' => 'wrong_password',
        ];

        $response = $this->postJson(route('user.delete'), $data);
        $response->assertUnprocessable();

        $this->assertDatabaseHas('users', ['id' => $this->user->id]);
    }
}
