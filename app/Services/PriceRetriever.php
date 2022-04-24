<?php

declare(strict_types = 1);

namespace App\Services;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Collection as SupportCollection;
use Illuminate\Support\Facades\Http;

class PriceRetriever
{
    public function __construct(
        protected ObservedGamesRetriever $retirever
    ) {}

    public function get(Collection|array $games = null): SupportCollection
    {
        $games = $games ?? $this->retirever->get();

        $appIds = $this->getAppIds($games);
        $prices = $this->getPrices($appIds);

        return $prices;
    }

    protected function getPrices(string $appIds): SupportCollection
    {
        $response = Http::get('https://store.steampowered.com/api/appdetails', $this->getParams($appIds));
        $content = $response->collect();

        $content->transform(function ($item) {
            $finalPrice = $item['data']['price_overview']['final'] ?? 0;

            return $finalPrice;
        });

        return $content;
    }

    protected function getAppIds(Collection $games): string
    {
        $charsToReplace = [
            '[' => '',
            ']' => '',
        ];

        $appIds = $games->pluck('appid')->__toString();
        $appIds = strtr($appIds, $charsToReplace);

        return $appIds;
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
