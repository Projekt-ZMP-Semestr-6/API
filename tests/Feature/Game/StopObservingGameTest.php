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

class StopObservingGameTest extends TestCase
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
        $this->uri = route('game.observe.detach', $this->game->appid);

        $this->user->observedGames()->syncWithPivotValues($this->game, ['initial_price' => $this->initialPrice]);

        $this->instance(
            PriceRetriever::class,
            Mockery::mock(PriceRetriever::class, function (MockInterface $mock) {
                $mock->shouldReceive('get')->withAnyArgs()->andReturn(Collection::make([$this->game->appid => 1010]));
            })
        );

        $this->instance(
            GamePriceUpdater::class,
            Mockery::mock(GamePriceUpdater::class, function (MockInterface $mock) {
                $mock->shouldReceive('update')->withAnyArgs()->andReturn(Collection::empty());
            })
        );
    }

    public function test_user_can_stop_observing_game(): void
    {
        Sanctum::actingAs($this->user);

        $this->assertDatabaseHas('game_user', [
            'user_id' => $this->user->id,
            'game_id' => $this->game->id,
            'initial_price' => $this->initialPrice,
        ]);

        $response = $this->getJson($this->uri);
        $response->assertOk();

        $this->assertEmpty($response->json());

        $this->assertDatabaseMissing('game_user', [
            'user_id' => $this->user->id,
            'game_id' => $this->game->id,
            'initial_price' => $this->initialPrice,
        ]);
    }

    public function test_unverified_user_cant_stop_observing_game(): void
    {
        $user = User::factory()->unverified()->create();

        Sanctum::actingAs($user);

        $this->assertDatabaseHas('game_user', [
            'user_id' => $this->user->id,
            'game_id' => $this->game->id,
            'initial_price' => $this->initialPrice,
        ]);

        $response = $this->getJson($this->uri);
        $response->assertForbidden();

        $this->assertSame(
            'Your email address is not verified.',
            $response->json('message')
        );
    }

    public function test_guest_cant_stop_observing_game(): void
    {
        $this->assertDatabaseHas('game_user', [
            'user_id' => $this->user->id,
            'game_id' => $this->game->id,
            'initial_price' => $this->initialPrice,
        ]);

        $response = $this->getJson($this->uri);
        $response->assertUnauthorized();
    }
}
