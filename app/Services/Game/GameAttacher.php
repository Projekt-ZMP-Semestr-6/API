<?php

namespace App\Services\Game;

use App\Exceptions\Game\AttachingGameException;
use App\Models\Game;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

class GameAttacher
{
    public function __construct(
        protected PriceRetriever $retriever,
        protected GamePriceUpdater $updater,
    ) {}

    public function attach(User $user, Game $game): void
    {
        $hasNoObservators = $game->observedBy()->doesntExist();

        $result = $user->observedGames()->syncWithoutDetaching($game);

        if ($result['attached']) {
            $user->observedGames()->updateExistingPivot($game->id, [
                'initial_price' => $this->getInitialPrice($game),
            ]);
        }

        empty($result['detached']) ?? throw new AttachingGameException;

        if ($hasNoObservators) {
            $this->updatePrice($game);
        }
    }

    private function updatePrice(Game $game): void
    {
        $games = Collection::make([$game]);

        $prices = $this->retriever->get($games);
        $this->updater->update($prices);
    }

    private function getInitialPrice(Game $game): int
    {
        $games = Collection::make([$game]);

        $prices = $this->retriever->get($games);
        $price = $prices->get($game->appid);

        return $price;
    }
}
