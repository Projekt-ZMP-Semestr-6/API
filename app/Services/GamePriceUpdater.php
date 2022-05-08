<?php

declare(strict_types = 1);

namespace App\Services;

use App\Events\PriceChanged;
use App\Models\Game;
use Illuminate\Support\Collection;

class GamePriceUpdater
{
    public function __construct(
        protected Collection $changedGames,
        protected array $lastPrice = [],
    ) {
        $this->changedGames = Collection::make();
    }

    public function update(Collection $prices): void
    {
        $appids = $prices->keys();
        $games = Game::whereIn('appid', $appids)->get();

        foreach($games as $game)
        {
            $this->lastPrice = [];
            $foundPrice = (int) $prices->get($game->appid);

            $hasChanged = $this->resolveActualPrice($game, $foundPrice);
            $this->resolveLowestPrice($game, $foundPrice);
            $this->resolveHighestPrice($game, $foundPrice);

            if ($hasChanged) {
                event(new PriceChanged($game, $this->lastPrice));
            }
        }
    }

    private function resolveActualPrice(Game $game, int $foundPrice): bool
    {
        $actualPrice = $game->actualPrice()->firstOrCreate(
            values: ['price' => $foundPrice],
        );

        if ($foundPrice === $actualPrice->price) {
            return false;
        }

        $this->lastPrice['price'] = $actualPrice->price;
        $this->lastPrice['date'] = $actualPrice->updated_at;

        return $actualPrice->update([
            'price' => $foundPrice,
        ]);
    }

    private function resolveLowestPrice(Game $game, int $foundPrice): void
    {

        $lowestPrice = $game->lowestPrice()->firstOrCreate(
            values: ['price' => $foundPrice]
        );

        if ($foundPrice > $lowestPrice->price) {
            return;
        }

        $lowestPrice->update([
            'price' => $foundPrice,
        ]);
    }

    private function resolveHighestPrice(Game $game, int $foundPrice): void
    {
        $highestPrice = $game->highestPrice()->firstOrCreate(
            values: ['price' => $foundPrice]
        );

        if ($foundPrice < $highestPrice->price) {
            return;
        }

        $highestPrice->update([
            'price' => $foundPrice,
        ]);
    }
}
