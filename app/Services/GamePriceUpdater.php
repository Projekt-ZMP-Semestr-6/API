<?php

declare(strict_types = 1);

namespace App\Services;

use App\Models\Game;
use Illuminate\Support\Collection;

class GamePriceUpdater
{
    public function __construct(
        protected Collection $reducedPrices
    ) {
        $this->reducedPrices = Collection::make();
    }

    public function update(Collection $prices): Collection
    {
        $appids = $prices->keys();
        $games = Game::whereIn('appid', $appids)->get();

        foreach($games as $game)
        {
            $foundPrice = (int) $prices->get($game->appid);

            $this->resolveActualPrice($game, $foundPrice);
            $this->resolveLowestPrice($game, $foundPrice);
            $this->resolveHighestPrice($game, $foundPrice);
        }

        return $this->reducedPrices;
    }

    private function resolveActualPrice(Game $game, int $foundPrice)
    {
        $actualPrice = $game->actualPrice()->firstOrCreate();

        $actualPrice->price = $foundPrice;

        $actualPrice->save();
    }

    private function resolveLowestPrice(Game $game, int $foundPrice)
    {

        $lowestPrice = $game->lowestPrice()->firstOrCreate(
            values: ['price' => $foundPrice]
        );

        if ($foundPrice < $lowestPrice->price) {
            $lowestPrice->update([
                'price' => $foundPrice,
            ]);

            $this->reducedPrices->push($game->appid);
        }

        return;
    }

    private function resolveHighestPrice(Game $game, int $foundPrice)
    {
        $highestPrice = $game->highestPrice()->firstOrCreate(
            values: ['price' => $foundPrice]
        );

        if ($foundPrice < $highestPrice->price) {
            return;
        }

        $highestPrice->price = $foundPrice;
        $highestPrice->save();
    }
}
