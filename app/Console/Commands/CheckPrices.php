<?php

declare(strict_types = 1);

namespace App\Console\Commands;

use App\Services\Game\GamePriceUpdater;
use App\Services\Game\PriceRetriever;
use Illuminate\Console\Command;

class CheckPrices extends Command
{
    protected $signature = 'games:check';

    protected $description = 'Check prices of games that are being observed';

    public function handle(PriceRetriever $retriever, GamePriceUpdater $updater): void
    {
        $prices = $retriever->get();

        $updater->update($prices);
    }
}
