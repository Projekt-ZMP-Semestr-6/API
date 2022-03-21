<?php

declare(strict_types = 1);

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ChangeNameTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        Sanctum::actingAs($this->user);
    }

    public function test_user_can_update_name(): void
    {
        $data = [
            'name' => 'Bob4',
        ];

        $response = $this->postJson(route('user.change.name'), $data);
        $response->assertOk();

        $this->user->refresh();
        $this->assertSame($data['name'], $this->user->name);
    }

    public function test_empty_request_is_rejected(): void
    {
        $response = $this->postJson(route('user.change.name'));
        $response->assertUnprocessable();
    }

    public function test_invalid_request_cant_pass_validation(): void
    {
        $data = [
            'name' => 'bob',
        ];

        $response = $this->postJson(route('user.change.name', $data));
        $response->assertUnprocessable();
    }
}
