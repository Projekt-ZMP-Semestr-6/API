<?php

declare(strict_types = 1);

namespace App\Services\Game;

use App\Models\Game;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class ObservedGamesRetriever
{
    public function get(): Collection
    {
        $ids = DB::table('game_user')->pluck('game_id')->unique();

        $games = Game::whereIn('id', $ids)->get();

        return $games;
    }
}
