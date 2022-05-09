<?php

namespace Tests\Feature\Game;

use App\Models\Game;
use App\Models\User;
use App\Services\ShowBestsellersService;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Mockery;
use Mockery\MockInterface;
use Tests\TestCase;

class ShowBestsellersTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;
    protected string $uri;
    protected Collection $expectedResponse;

    protected function setUp(): void
    {
        parent::setUp();

        $this->uri = route('game.bestsellers');
        $this->user = User::factory()->create();

        $this->expectedResponse = Collection::make(
            json_decode(
                file_get_contents('tests/Responses/game_bestsellers_200.json'),
                true,
            )
        );

        $this->expectedResponse->transform(function ($item, $key) {
            return Game::make($item);
        });

        $this->instance(
            ShowBestsellersService::class,
            Mockery::mock(ShowBestsellersService::class, function (MockInterface $mock) {
                $mock->shouldReceive('get')->withNoArgs()->andReturn($this->expectedResponse);
            })
        );
    }

    public function test_user_can_retrieve_bestsellers(): void
    {
        Sanctum::actingAs($this->user);

        $response = $this->getJson($this->uri);
        $response->assertOk();
        $response->assertJson($this->expectedResponse->toArray());
    }

    public function test_unverified_user_cant_retrieve_freebies(): void
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

    public function test_guest_cant_retrieve_freebies(): void
    {
        $response = $this->getJson($this->uri);
        $response->assertUnauthorized();
    }
}
