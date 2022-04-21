<?php

declare(strict_types = 1);

namespace App\Http\Controllers\Game;

use App\Http\Controllers\Controller;
use App\Services\SearchGameService;
use Illuminate\Http\JsonResponse;

/**
 * @OA\Get(
 * path="/api/search/{gameName}",
 * summary="Search game",
 * description="Search for game with the given title",
 * operationId="gameSearch",
 * tags={"Game"},
 * security={{"sanctum": {}}},
 * @OA\Parameter(
 *      name="gameName",
 *      in="path",
 *      description="Game's title",
 *      required=true,
 *      example="batman",
 * ),
 * @OA\Response(
 *      response=200,
 *      description="OK",
 *      @OA\JsonContent(
 *          type="array",
 *          @OA\Items(
 *              ref="#/components/schemas/Game"
 *          ),
 *      ),
 * ))
 */
class SearchGameController extends Controller
{
    public function __invoke(SearchGameService $service, string $gameName): JsonResponse
    {
        $games = $service->searchFor($gameName);

        return new JsonResponse($games);
    }
}
