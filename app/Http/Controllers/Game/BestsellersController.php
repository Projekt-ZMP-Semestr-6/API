<?php

declare(strict_types = 1);

namespace App\Http\Controllers\Game;

use App\Http\Controllers\Controller;
use App\Http\Resources\GameResource;
use App\Services\Game\ShowBestsellersService;
use Illuminate\Http\JsonResponse;

/**
 * @OA\Get(
 * path="/api/bestsellers",
 * summary="Show bestsellers",
 * description="Get list of current bestsellers",
 * operationId="gameBestsellers",
 * tags={"Game"},
 * security={{"sanctum": {}}},
 * @OA\Response(
 *      response=200,
 *      description="OK",
 *      @OA\JsonContent(
 *          type="array",
 *          @OA\Items(
 *              ref="#/components/schemas/Game"
 *          )
 *      )
 * ))
 */
class BestsellersController extends Controller
{
    public function __invoke(ShowBestsellersService $service): JsonResponse
    {
        $games = $service->get();

        $games = GameResource::collection($games);

        return new JsonResponse($games);
    }
}
