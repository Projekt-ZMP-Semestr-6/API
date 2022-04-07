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
class SearchGameController extends Controller
{
    public function __invoke(SearchGameService $service, string $gameName): JsonResponse
    {
        $found = $service->searchFor($gameName);

        return new JsonResponse($found);
    }
}
