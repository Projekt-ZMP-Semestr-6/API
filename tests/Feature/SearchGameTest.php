<?php

declare(strict_types = 1);

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class SearchGameTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;
    protected string $uri;
    protected mixed $expectedResponse;

    protected function setUp(): void
    {
        parent::setUp();

        $this->uri = route('game.search', 'batman');
        $this->user = User::factory()->create();
        $this->expectedResponse = json_decode(file_get_contents('tests/responses/search_game_200.json'), true);

        Http::fake([
            env('EXTERNAL_API') => Http::response($this->expectedResponse)
        ]);
    }

    public function test_user_can_search_for_game(): void
    {
        Sanctum::actingAs($this->user);

        $response = $this->getJson($this->uri);
        $response->assertOk();
        $response->assertJson($this->expectedResponse);
    }

    public function test_unverified_user_cant_search_for_game(): void
    {
        $this->user = User::factory()->unverified()->create();

        Sanctum::actingAs($this->user);

        $response = $this->getJson($this->uri);
        $response->assertForbidden();

        $this->assertSame(
            'Your email address is not verified.',
            $response->json('message')
        );
    }

    public function test_guest_cant_search_for_game(): void
    {
        $response = $this->getJson($this->uri);
        $response->assertUnauthorized();
    }
}
