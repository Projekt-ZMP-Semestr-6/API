<?php

declare(strict_types = 1);

namespace Tests\Feature\Game;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Collection;
use Laravel\Sanctum\Sanctum;
use Mockery;
use Mockery\MockInterface;
use Tests\TestCase;

class ShowFreebiesTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;
    protected string $uri;
    protected Collection $expectedResponse;

    protected function setUp(): void
    {
        parent::setUp();

        $this->uri = route('game.freebies');
        $this->user = User::factory()->create();

        $this->expectedResponse = Collection::make(
            json_decode(
                file_get_contents('tests/Responses/game_freebies_200.json'),
                true
            )
        );

        $this->instance(
            ShowFreebiesService::class,
            Mockery::mock(ShowFreebiesService::class, function (MockInterface $mock) {
                $mock->shouldReceive('get')->withNoArgs()->andReturn($this->expectedResponse);
            })
        );
    }

    public function test_user_can_retrieve_freebies(): void
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
