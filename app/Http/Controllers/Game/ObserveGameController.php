<?php

declare(strict_types = 1);

namespace App\Http\Controllers\Game;

use App\Http\Controllers\Controller;
use App\Models\Game;
use App\Services\Game\GameAttacher;
use App\Services\Game\GameDetacher;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * @OA\Get(
 * path="/api/attach/{appid}",
 * summary="Attach game",
 * description="Attach game to observing its price",
 * operationId="attachGame",
 * tags={"Game"},
 * security={{"sanctum": {}}},
 * @OA\Parameter(
 *      name="appid",
 *      in="path",
 *      description="Game's appId",
 *      required=true,
 *      example="21000",
 * ),
 * @OA\Response(
 *      response=200,
 *      description="OK",
 * )),
 * @OA\Get(
 * path="/api/detach/{appid}",
 * summary="Detach game",
 * description="Detach game from observing its price",
 * operationId="detachGame",
 * tags={"Game"},
 * security={{"sanctum": {}}},
 * @OA\Parameter(
 *      name="appid",
 *      in="path",
 *      description="Game's appId",
 *      required=true,
 *      example="21000",
 * ),
 * @OA\Response(
 *      response=200,
 *      description="OK",
 * )),
 */
class ObserveGameController extends Controller
{
    public function attach(Request $request, Game $game, GameAttacher $attacher): JsonResponse
    {
        $user = $request->user('sanctum');

        $attacher->attach($user, $game);

        return new JsonResponse();
    }

    public function detach(Request $request, Game $game, GameDetacher $detacher): JsonResponse
    {
        $user = $request->user('sanctum');

        $detacher->detach($user, $game);

        return new JsonResponse();
    }
}
