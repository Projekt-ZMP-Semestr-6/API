<?php

declare(strict_types = 1);

namespace App\Services;

use App\Http\Clients\GameClient;

class ShowGameService
{
    protected GameClient $client;

    public function __construct()
    {
        $this->client = new GameClient();
    }

    public function get(int $gameId): mixed
    {
        $uri = env('EXTERNAL_API') . 'games';
        $options = $this->buildOptions($gameId);

        $response = $this->client->get($uri, $options);

        return $response;
    }

    protected function buildOptions(int $gameId): array
    {
        return [
            'verify' => false,
            'query' => [
                'id' => $gameId,
            ],
        ];
    }
}
