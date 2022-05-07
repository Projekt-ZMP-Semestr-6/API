<?php

declare(strict_types = 1);

namespace App\Console\Commands;

use App\Services\GamePriceUpdater;
use App\Services\PriceRetriever;
use Illuminate\Console\Command;

class CheckPrices extends Command
{
    protected $signature = 'games:check';

    protected $description = 'Check prices of games that are being observed';

    public function handle(PriceRetriever $retriever, GamePriceUpdater $updater): void
    {
        $prices = $retriever->get();

        $changedGames = $updater->update($prices);

        //$notifier->notify($reducedPrices);
    }
}
