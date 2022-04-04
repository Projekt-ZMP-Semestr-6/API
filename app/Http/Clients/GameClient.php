<?php

declare(strict_types = 1);

namespace App\Http\Clients;

use GuzzleHttp\Client;

class GameClient
{
    protected Client $client;

    public function __construct()
    {
        $this->client = new Client();
    }

    public function get(string $uri, array $options = []): mixed
    {
        $response = $this->client->request('get', $uri, $options);
        $contents = json_decode($response->getBody()->getContents());

        return $contents;
    }
}
