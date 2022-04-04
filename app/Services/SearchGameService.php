<?php

declare(strict_types = 1);

namespace App\Services;

use App\Http\Clients\GameClient;

class SearchGameService
{
    protected GameClient $client;

    public function __construct()
    {
        $this->client = new GameClient();
    }

    public function searchFor(string $gameTitle): mixed
    {
        $uri = env('EXTERNAL_API') . 'games';
        $options = $this->buildOptions($gameTitle);

        $found = $this->client->get($uri, $options);

        return $found;
    }

    protected function buildOptions(string $gameTitle): array
    {
        return [
            'verify' => false,
            'query' => [
                'title' => $gameTitle,
                'limit' => 5,
                'exact' => 0,
            ],
        ];
    }
}
