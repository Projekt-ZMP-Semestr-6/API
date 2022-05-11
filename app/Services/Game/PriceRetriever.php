<?php

declare(strict_types = 1);

namespace App\Services\Game;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;

class PriceRetriever
{
    public function __construct(
        protected ObservedGamesRetriever $retriever,
    ) {}

    public function get(Collection $games = null): Collection
    {
        $games = $games ?? $this->retriever->get();

        $appIds = $this->getAppIds($games);
        $prices = $this->getPrices($appIds);

        return $prices;
    }

    protected function getPrices(string $appIds): Collection
    {
        $response = Http::get('https://store.steampowered.com/api/appdetails', $this->getParams($appIds));
        $content = $response->collect();

        $content->transform(function ($item) {
            return $item['data']['price_overview']['final'] ?? 0;
        });

        return $content;
    }

    protected function getAppIds(Collection $games): string
    {
        $charsToReplace = [
            '[' => '',
            ']' => '',
        ];

        return strtr(
            $games->pluck('appid')->__toString(),
            $charsToReplace
        );
    }

    protected function getParams(string $appIds): array
    {
        return [
            'key'=> env('API_KEY'),
            'appids'=> $appIds,
            'filters' => 'price_overview',
            'cc' => 'en',
        ];
    }

}
