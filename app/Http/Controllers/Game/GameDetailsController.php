<?php

declare(strict_types = 1);

namespace App\Http\Controllers\Game;

use App\Http\Controllers\Controller;
use App\Services\ShowGameService;
use Illuminate\Http\JsonResponse;

/**
 * @OA\Get(
 * path="/api/game/{gameId}",
 * summary="Game details",
 * description="Get deal's details for specific game",
 * operationId="gameDetails",
 * tags={"Game"},
 * security={{"sanctum": {}}},
 * @OA\Parameter(
 *      name="gameId",
 *      in="path",
 *      description="Game's ID",
 *      required=true,
 *      example="167613",
 * ),
 * @OA\Response(
 *      response=200,
 *      description="OK",
 *      @OA\JsonContent(
 *          @OA\Property(
 *              property="info",
 *              type="array",
 *              @OA\Items(
 *                  @OA\Property(property="title", type="string", example="LEGO Batman"),
 *                  @OA\Property(property="steamAppID", type="string", example="21000"),
 *                  @OA\Property(property="thumb", type="string", example="https://originassets.akamaized.net/origin-com-store-final-assets-prod/195763/142.0x200.0/1040463_MB_142x200_en_US_^_2017-09-08-15-21-36_d7034d41216b6dc201fb20e0cee37c1e66190a11.jpg"),
 *              ),
 *          ),
 *          @OA\Property(
 *              property="cheapestPriceEver",
 *              type="array",
 *              @OA\Items(
 *                  @OA\Property(property="price", type="string", example="3.99"),
 *                  @OA\Property(property="date", type="date:unix_timestamp", example="1543028665"),
 *              ),
 *          ),
 *          @OA\Property(
 *              property="deals",
 *              type="array",
 *              @OA\Items(
 *                  @OA\Property(property="storeID", type="string", example="23"),
 *                  @OA\Property(property="dealID", type="string", example="tyTH88J0PXRvYALBjV3cNHd5Juq1qKcu4tG4lBiUCt4%3D"),
 *                  @OA\Property(property="price", type="string", example="14.95"),
 *                  @OA\Property(property="retailPrice", type="string", example="19.99"),
 *                  @OA\Property(property="savings", type="string", example="25.212606"),
 *              ),
 *          ),
 *      ),
 * ))
 */
class GameDetailsController extends Controller
{
    public function __invoke(ShowGameService $service, int $gameId): JsonResponse
    {
        $result = $service->get($gameId);

        return new JsonResponse($result);
    }
}
