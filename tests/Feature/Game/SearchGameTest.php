<?php

declare(strict_types = 1);

namespace Tests\Feature\Game;

use App\Models\Game;
use App\Models\User;
use App\Services\SearchGameService;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Mockery;
use Mockery\MockInterface;
use Tests\TestCase;

class SearchGameTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;
    protected string $uri;
    protected Collection $expectedResponse;

    protected function setUp(): void
    {
        parent::setUp();

        $this->uri = route('game.search', 'batman');
        $this->user = User::factory()->create();

        $this->expectedResponse = Game::hydrate(
            json_decode(
                file_get_contents('tests/Responses/search_game_200.json'),
                true,
            )
        );

        $this->instance(
            SearchGameService::class,
            Mockery::mock(SearchGameService::class, function (MockInterface $mock) {
                $mock->shouldReceive('searchFor')->with('batman')->andReturn($this->expectedResponse);
            })
        );
    }

    public function test_user_can_search_for_game(): void
    {
        Sanctum::actingAs($this->user);

        $response = $this->getJson($this->uri);
        $response->assertOk();
        $response->assertJson($this->expectedResponse->toArray());
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
