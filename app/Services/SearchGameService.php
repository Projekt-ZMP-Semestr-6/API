<?php

declare(strict_types = 1);

namespace App\Services;

use App\Models\Game;
use Illuminate\Database\Eloquent\Collection;

class SearchGameService
{
    public function searchFor(string $gameName): Collection
    {
        $gameName = strtr($gameName, ' ', '%');

        $games = Game::where('name', 'ilike', "%$gameName%")->limit(5)->get();

        return $games;
    }
}
