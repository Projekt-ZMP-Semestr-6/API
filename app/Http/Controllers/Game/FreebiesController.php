<?php

declare(strict_types = 1);

namespace App\Http\Controllers\Game;

use App\Http\Controllers\Controller;
use App\Http\Resources\GameResource;
use App\Services\Game\ShowFreebiesService;
use Illuminate\Http\JsonResponse;

/**
 * @OA\Get(
 * path="/api/freebies",
 * summary="Show freebies",
 * description="Get list of currently free games",
 * operationId="gameFreebies",
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
class FreebiesController extends Controller
{
    public function __invoke(ShowFreebiesService $service): JsonResponse
    {
        $games = $service->get();

        $games = GameResource::collection($games);

        return new JsonResponse($games);
    }
}
