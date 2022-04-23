<?php

declare(strict_types = 1);

namespace App\Services;

use App\Exceptions\Game\GameDetailsNotRetrievedException;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;

class ShowGameService
{
    public function get(int $appId): Collection
    {
        $response = Http::get("https://store.steampowered.com/api/appdetails", [
            'key' => env('API_KEY'),
            'appids' => $appId,
            'cc' => 'en',
        ]);

        $gameDetails = $this->processResponse($response, $appId);

        return $gameDetails;
    }

    protected function processResponse(Response $response, int $appId): Collection
    {
        $response->collect("$appId")->get('success') ?? throw new GameDetailsNotRetrievedException;

        $gameDetails = $response->collect("$appId.data")->only([
            'type',
            'name',
            'steam_appid',
            'controller_support',
            'detailed_description',
            'about_the_game',
            'short_description',
            'supported_languages',
            'header_image',
            'developers',
            'publishers',
            'price_overview',
            'platforms',
            'release_date',
            'background',
            'screenshots',
        ]);

        return $gameDetails;
    }
}
