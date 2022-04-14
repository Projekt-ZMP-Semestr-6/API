<?php

declare(strict_types = 1);

namespace App\Console\Commands;

use App\Services\GamesFetcher;
use Illuminate\Console\Command;

class FetchGames extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'games:fetch';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "Fetch and persist games' info from external API";

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(GamesFetcher $fetcher)
    {
        $fetcher->fetch();
    }
}
