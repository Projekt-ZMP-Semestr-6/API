<?php

namespace App\Services\Game;

use App\Exceptions\Game\DetachingGameException;
use App\Models\Game;
use App\Models\User;

class GameDetacher
{
    public function detach(User $user, Game $game): void
    {
        $detached = $user->observedGames()->detach($game);

        $stillAttached = in_array($game, $user->observedGames->toArray());

        if (!$detached || $stillAttached) {
            throw new DetachingGameException;
        }
    }
}
