<?php

declare(strict_types = 1);

namespace Tests\Feature\Game;

use App\Models\Game;
use App\Models\User;
use App\Services\GamePriceUpdater;
use App\Services\PriceRetriever;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Collection;
use Laravel\Sanctum\Sanctum;
use Mockery;
use Mockery\MockInterface;
use Tests\TestCase;

class ObserveGameTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;
    protected string $uri;
    protected Game $game;
    protected int $initialPrice = 1010;

    protected function setUp(): void
    {
        parent::setUp();

        $this->game = Game::factory()->batman()->create();
        $this->user = User::factory()->create();
        $this->uri = route('game.observe.attach', $this->game->appid);

        $this->instance(
            PriceRetriever::class,
            Mockery::mock(PriceRetriever::class, function (MockInterface $mock) {
                $mock->shouldReceive('get')->withAnyArgs()->andReturn(Collection::make([$this->game->appid => $this->initialPrice]));
            })
        );

        $this->instance(
            GamePriceUpdater::class,
            Mockery::mock(GamePriceUpdater::class, function (MockInterface $mock) {
                $mock->shouldReceive('update')->withAnyArgs()->andReturn(Collection::empty());
            })
        );
    }

    public function test_user_can_observe_game(): void
    {
        Sanctum::actingAs($this->user);

        $response = $this->getJson($this->uri);
        $response->assertOk();

        $this->assertEmpty($response->json());

        $this->assertDatabaseHas('game_user', [
            'user_id' => $this->user->id,
            'game_id' => $this->game->id,
            'initial_price' => $this->initialPrice,
        ]);
    }

    public function test_unverified_user_cant_observe_game(): void
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

    public function test_guest_cant_observe_game(): void
    {
        $response = $this->getJson($this->uri);
        $response->assertUnauthorized();
    }
}
