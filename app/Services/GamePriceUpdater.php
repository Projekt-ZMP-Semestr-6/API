<?php

declare(strict_types = 1);

namespace App\Services;

use App\Models\Game;
use App\Models\Price;
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
            $price = $game->price()->firstOrCreate();
            $actualPrice = (int) $prices->get($game->appid);

            if($price->wasRecentlyCreated) {
                $price->update([
                    'actual_price' => $actualPrice,
                    'lowest_price' => $actualPrice,
                    'highest_price' => $actualPrice,
                ]);

                continue;
            }

            $price->actual_price = $actualPrice;
            $price->lowest_price = $this->resolveLowestPrice($price, $actualPrice);
            $price->highest_price = $this->resolveHighestPrice($price, $actualPrice);

            $price->save();
        }

        return $this->reducedPrices;
    }

    private function resolveLowestPrice(Price $price, int $actualPrice): int
    {
        $lowestPrice = $price->lowest_price;

        if($actualPrice < $lowestPrice) {
            $this->reducedPrices->push($price);

            return $actualPrice;
        }

        return $lowestPrice;
    }

    private function resolveHighestPrice(Price $price, int $actualPrice): int
    {
        $highestPrice = $price->highest_price;

        if($actualPrice > $highestPrice) {
            return $actualPrice;
        }

        return $highestPrice;
    }
}
