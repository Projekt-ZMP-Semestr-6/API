<?php

declare(strict_types = 1);

namespace App\Http\Controllers;

use App\Models\ActualPrice;
use Illuminate\Support\Facades\Artisan;

class SimulateChangesController extends Controller
{
    public function __invoke(int $price): void
    {
        ActualPrice::all()->transform(function ($item) use ($price) {
            $item->update(['price' => $price]);
        });

        Artisan::call('games:check');
    }
}
