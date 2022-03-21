<?php

declare(strict_types = 1);

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ChangePasswordTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        Sanctum::actingAs($this->user);
    }

    public function test_user_can_update_password(): void
    {
        $data = [
            'old_password' => 'password',
            'new_password' => 'password123',
            'new_password_confirmation' => 'password123',
        ];

        $response = $this->postJson(route('user.change.password'), $data);
        $response->assertOk();

        $this->user->refresh();
        $this->assertTrue(Hash::check($data['new_password'], $this->user->password));
    }

    public function test_empty_request_is_rejected(): void
    {
        $response = $this->postJson(route('user.change.password'));
        $response->assertUnprocessable();
    }

    public function test_invalid_request_cant_pass_validation(): void
    {
        $data = [
            'old_password' => 'wrong_password',
            'new_password' => 'password123',
            'new_password_confirmation' => 'password123',
        ];

        $response = $this->postJson(route('user.change.password'), $data);
        $response->assertUnprocessable();

        $data = [
            'old_password' => 'password',
            'new_password' => 'no_confirmation',
        ];

        $response = $this->postJson(route('user.change.password'), $data);
        $response->assertUnprocessable();

        $data = [
            'new_password' => 'no_old_password',
            'new_password_confirmation' => 'no_old_password',
        ];

        $response = $this->postJson(route('user.change.password'), $data);
        $response->assertUnprocessable();
    }
}
