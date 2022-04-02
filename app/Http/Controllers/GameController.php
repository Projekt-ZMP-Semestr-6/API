<?php

declare(strict_types = 1);

namespace App\Http\Controllers;

use App\Services\SearchGameService;
use App\Services\ShowGameService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class GameController extends Controller
{
    public function index(SearchGameService $service, string $gameName): JsonResponse
    {
        $found = $service->searchFor($gameName);

        return new JsonResponse($found);
    }

    public function store(Request $request)
    {
        //
    }

    public function show(ShowGameService $service, int $gameId): JsonResponse
    {
        $result = $service->get($gameId);

        return new JsonResponse($result);
    }

    public function update(Request $request, $id)
    {
        //
    }

    public function destroy($id)
    {
        //
    }
}
