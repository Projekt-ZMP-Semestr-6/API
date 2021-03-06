<?php

declare(strict_types = 1);

namespace App\Services\Game;

use App\Exceptions\Game\GamesNotFetchedException;
use App\Models\Game;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;

class GamesFetcher
{
    protected Collection $games;
    protected int $lastAppid = 0;

    public function process(): void
    {
        $response = $this->fetch();
        $haveMoreResults = $this->processResponse($response);
        $this->persist();

        if($haveMoreResults) {
            $this->process();
        }
    }

    protected function fetch(): Response
    {
        return Http::get('https://api.steampowered.com/IStoreService/GetAppList/v1/', [
            'key' => env('API_KEY'),
            'max_results' => 50000,
            'last_appid' => $this->lastAppid,
            'cc' => 'en',
        ]);
    }

    protected function processResponse(Response $response): bool
    {
        $this->lastAppid = (int) $response->collect('response')->get('last_appid');
        $this->games = $response->collect('response.apps');

        $this->games ?? throw new GamesNotFetchedException;

        return (bool) $response->collect('response')->get('have_more_results');
    }

    protected function persist(): void
    {
        $this->games->map(function ($item) {
            if (strlen($item['name']) <= 255) {
                Game::updateOrCreate(
                    array('appid' => $item['appid']),
                    $item,
                );
            }
        });
    }
}
