<?php

declare(strict_types = 1);

namespace App\Console\Commands;

use App\Services\GamesFetcher;
use Illuminate\Console\Command;

class FetchGames extends Command
{
    protected $signature = 'games:fetch';

    protected $description = "Fetch and persist games' info from external API";

    public function handle(GamesFetcher $fetcher): void
    {
        $fetcher->process();
    }
}
