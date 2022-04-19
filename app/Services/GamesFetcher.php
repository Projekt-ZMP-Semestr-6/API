<?php

declare(strict_types = 1);

namespace App\Services;

use App\Exceptions\Game\GamesNotFetchedException;
use App\Models\Game;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;

class GamesFetcher
{
    protected Collection $games;
    protected Collection $knownGames;

    public function fetch(): void
    {
        $response = Http::get('http://api.steampowered.com/ISteamApps/GetAppList/v2', ['key' => env('API_KEY')]);
        $this->games = $response->collect('applist.apps');

        if (!$this->games) {
            throw new GamesNotFetchedException;
        }

        $this->getUnknownGames();
        $this->updateNames();
        $this->persist();
    }

    protected function getUnknownGames(): void
    {
        Game::chunkById(100, function ($gamesFromDB) {
            $this->knownGames = $this->games->whereIn('appid', $gamesFromDB->pluck('appid'), true);

            $keys = $this->knownGames->keys()->toArray();
            $this->games->forget($keys);
        });
    }

    protected function updateNames(): void
    {
        $plucked = $this->knownGames->pluck('name');
        $outdated = $this->knownGames->whereNotIn('name', $plucked, true);

        foreach($outdated as $game)
        {
            Game::where('appid', $game['appid'])
                ->update(['name' => $game['name']]);
        }
    }

    protected function persist(): void
    {
        $this->games->map(function ($item) {
            Game::create($item);
        });
    }
}
