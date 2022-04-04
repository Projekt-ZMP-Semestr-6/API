<?php

declare(strict_types = 1);

namespace App\Http\Controllers;

use App\Services\SearchGameService;
use App\Services\ShowGameService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

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
 *
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
 *                  @OA\Property(property="title" ,type="string", example="LEGO Batman"),
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
 * ),
 * ),
 *
 * @OA\Schema(
 * schema="Game",
 * type="object",
 * @OA\Property(property="gameID", type="string", example="612"),
 * @OA\Property(property="steamAppID", type="string", example="21000"),
 * @OA\Property(property="cheapest", type="string", example="14.95"),
 * @OA\Property(property="cheapestDealID", type="string", example="tyTH88J0PXRvYALBjV3cNHd5Juq1qKcu4tG4lBiUCt4%3D"),
 * @OA\Property(property="external", type="string", example="LEGO Batman"),
 * @OA\Property(property="internalName", type="string", example="LEGOBATMAN"),
 * @OA\Property(property="thumb", type="string", example="https://originassets.akamaized.net/origin-com-store-final-assets-prod/195763/142.0x200.0/1040463_MB_142x200_en_US_^_2017-09-08-15-21-36_d7034d41216b6dc201fb20e0cee37c1e66190a11.jpg"),
 * )
 */
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
