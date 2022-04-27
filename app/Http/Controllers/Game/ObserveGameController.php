<?php

declare(strict_types = 1);

namespace App\Http\Controllers\Game;

use App\Exceptions\Game\AttachingGameException;
use App\Exceptions\Game\DetachingGameException;
use App\Http\Controllers\Controller;
use App\Models\Game;
use App\Services\GamePriceUpdater;
use App\Services\PriceRetriever;
use Illuminate\Database\Eloquent\Collection;
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
    public function __construct(
        protected PriceRetriever $retriever,
        protected GamePriceUpdater $updater,
    ) {}

    public function attach(Request $request, Game $game): JsonResponse
    {
        $user = $request->user('sanctum');

        $hasNoObservators = $game->observedBy()->doesntExist();

        $result = $user->observedGames()->syncWithoutDetaching($game);

        if ($result['attached']) {
            $user->observedGames()
                ->updateExistingPivot($game->id, [
                    'initial_price' => $this->getInitialPrice($game),
                ]);
        }

        empty($result['detached']) ?? throw new AttachingGameException;

        if ($hasNoObservators) {
            $this->updatePrice($game);
        }

        return new JsonResponse();
    }

    public function detach(Request $request, Game $game): JsonResponse
    {
        $user = $request->user('sanctum');

        $detached = $user->observedGames()->detach($game);

        $stillAttached = in_array($game, $user->observedGames->toArray());

        if ($detached && $stillAttached) {
            throw new DetachingGameException;
        }

        return new JsonResponse();
    }

    private function updatePrice(Game $game): void
    {
        $games = Collection::empty()->push($game);

        $prices = $this->retriever->get($games);
        $this->updater->update($prices);
    }

    private function getInitialPrice(Game $game): int
    {
        $games = Collection::empty()->push($game);

        $prices = $this->retriever->get($games);

        return $prices->get($game->appid);
    }
}
