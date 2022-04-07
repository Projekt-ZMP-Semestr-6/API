<?php

declare(strict_types = 1);

namespace App\Http\Controllers\Game;

use App\Http\Controllers\Controller;
use App\Services\ShowFreebiesService;
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
 *              ref="#/components/schemas/Freebies"
 *          )
 *      )
 * ))
 *
 * @OA\Schema(
 * schema="Freebies",
 * type="object",
 * @OA\Property(property="internalName", type="string", example="TOTALWARWARHAMMER"),
 * @OA\Property(property="title", type="string", example="Total War: WARHAMMER"),
 * @OA\Property(property="metacriticLink", type="string", example="/game/pc/total-war-warhammer"),
 * @OA\Property(property="dealID", type="string", example="bm6WFBMh3kPMH1xYLQpstzUyTC%2BObrT2PB2k2osreAg%3D"),
 * @OA\Property(property="storeID", type="string", example="25"),
 * @OA\Property(property="gameID", type="string", example="144547"),
 * @OA\Property(property="salePrice", type="string", example="0.00"),
 * @OA\Property(property="normalPrice", type="string", example="59.99"),
 * @OA\Property(property="isOnSale", type="string", example="1"),
 * @OA\Property(property="savings", type="string", example="100.000000"),
 * @OA\Property(property="metacriticScore", type="string", example="86"),
 * @OA\Property(property="steamRatingText", type="string", example="Mostly Positive"),
 * @OA\Property(property="steamRatingPercent", type="string", example="78"),
 * @OA\Property(property="steamRatingCount", type="string", example="29367"),
 * @OA\Property(property="steamAppID", type="string", example="364360"),
 * @OA\Property(property="releaseDate", type="date:unix_timestamp", example="1464048000"),
 * @OA\Property(property="lastChange", type="date:unix_timestamp", example="1648742846"),
 * @OA\Property(property="dealRating", type="string", example="10.0"),
 * @OA\Property(property="thumb", type="string", example="https://cdn.cloudflare.steamstatic.com/steam/apps/364360/capsule_sm_120.jpg?t=1632304339"),
 * )
 */
class FreebiesController extends Controller
{
    public function __invoke(ShowFreebiesService $service): JsonResponse
    {
        $found = $service->get();

        return new JsonResponse($found);
    }
}
