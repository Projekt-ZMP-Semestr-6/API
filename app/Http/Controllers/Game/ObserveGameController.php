<?php

declare(strict_types = 1);

namespace App\Http\Controllers\Game;

use App\Exceptions\Game\AttachingGameException;
use App\Exceptions\Game\DetachingGameException;
use App\Http\Controllers\Controller;
use App\Models\Game;
use App\Services\GamePriceUpdater;
use App\Services\PriceRetriever;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * @OA\Get(
 * path="/api/observe/{appid}",
 * summary="Observe game",
 * description="Observe game's price",
 * operationId="observeGame",
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
 * ))
 */
class ObserveGameController extends Controller
{
    public function __invoke(Request $request, Game $game, PriceRetriever $retriever, GamePriceUpdater $updater): JsonResponse
    {
        $user = $request->user('sanctum');
        $results = $user->observedGames()->toggle($game);

        $this->hasSucceeded($results, $game->appid);

        $prices = $retriever->get([$game]);
        $updater->update($prices);

        return new JsonResponse();
    }

    protected function hasSucceeded(array $results, int $appid): void
    {
        $attached = $results['attached'];
        $detached = $results['detached'];

        in_array($appid, $attached) ?? throw new AttachingGameException;
        in_array($appid, $detached) ?? throw new DetachingGameException;
    }
}
