<?php

declare(strict_types = 1);

namespace App\Services;

use App\Http\Clients\GameClient;

class ShowFreebiesService
{
    protected GameClient $client;

    public function __construct()
    {
        $this->client = new GameClient();
    }

    public function get(): mixed
    {
        $uri = env('EXTERNAL_API') . 'deals';
        $options = $this->buildOptions();

        $found = $this->client->get($uri, $options);

        return $found;
    }

    protected function buildOptions(): array
    {
        return [
            'verify' => false,
            'query' => [
                'upperPrice' => 0,
            ],
        ];
    }
}
