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
 *      description="Game's appId",
 *      required=true,
 *      example="21000",
 * ),
 * @OA\Response(
 *      response=200,
 *      description="OK",
 *      @OA\JsonContent(
 *          @OA\Property(property="type", type="string", example="game"),
 *          @OA\Property(property="name", type="string", example="LEGO® Batman™: The Videogame"),
 *          @OA\Property(property="steam_appid", type="int", example="21000"),
 *          @OA\Property(property="controller_support", type="string", example="full"),
 *          @OA\Property(property="detailed_description", type="string", example="When all the villains in Arkham Asylum team up and break loose, only the dynamic duo is bold enough to take them on to save Gotham City. The fun of LEGO, the drama of Batman and the uniqueness of the combination makes for a comical and exciting adventure in LEGO Batman: The Videogame. Play as Batman and his sidekick Robin as you build, drive, swing and fight your way through Gotham City capturing escaped villains including The Joker, Penguin, Scarecrow and more. Then, jump into the story from the other side and play as Batmans foes! Enjoy the power you wield and battle Batman while spreading chaos throughout the city. There is no rest for the good (or evil!)."),
 *          @OA\Property(property="about_the_game", type="string", example="When all the villains in Arkham Asylum team up and break loose, only the dynamic duo is bold enough to take them on to save Gotham City. The fun of LEGO, the drama of Batman and the uniqueness of the combination makes for a comical and exciting adventure in LEGO Batman: The Videogame. Play as Batman and his sidekick Robin as you build, drive, swing and fight your way through Gotham City capturing escaped villains including The Joker, Penguin, Scarecrow and more. Then, jump into the story from the other side and play as Batmans foes! Enjoy the power you wield and battle Batman while spreading chaos throughout the city. There is no rest for the good (or evil!)."),
 *          @OA\Property(property="short_description", type="string", example="When all the villains in Arkham Asylum team up and break loose, only the dynamic duo is bold enough to take them on to save Gotham City. The fun of LEGO, the drama of Batman and the uniqueness of the combination makes for a comical and exciting adventure in LEGO Batman: The Videogame."),
 *          @OA\Property(property="supported_languages", type="string", example="English, French, Spanish - Spain"),
 *          @OA\Property(property="header_image", type="string", example="https://cdn.akamai.steamstatic.com/steam/apps/21000/header.jpg?t=1573509038"),
 *
 *          @OA\Property(
 *              property="developers",
 *              type="array",
 *              @OA\Items(type="string", example="Traveller's Tales"),
 *          ),
 *
 *          @OA\Property(
 *              property="publishers",
 *              type="array",
 *              @OA\Items(type="string", example="Warner Bros. Interactive Entertainment"),
 *          ),
 *
 *          @OA\Property(
 *              property="price_overview",
 *              @OA\Property(property="currency", type="string", example="USD"),
 *              @OA\Property(property="initial", type="int", example="1999"),
 *              @OA\Property(property="final", type="int", example="1999"),
 *              @OA\Property(property="discount_percent", type="int", example="0"),
 *              @OA\Property(property="initial_formatted", type="string", example=""),
 *              @OA\Property(property="final_formatted", type="string", example="$19.99 USD"),
 *          ),
 *
 *          @OA\Property(
 *              property="platforms",
 *              @OA\Property(property="windows", type="bool", example="true"),
 *              @OA\Property(property="mac", type="bool", example="false"),
 *              @OA\Property(property="linux", type="bool", example="false"),
 *          ),
 *
 *          @OA\Property(
 *              property="release_date",
 *              @OA\Property(property="coming_soon", type="bool", example="false"),
 *              @OA\Property(property="date", type="date", example="29 Sep, 2008"),
 *          ),
 *
 *          @OA\Property(property="background", type="string", example="https://cdn.akamai.steamstatic.com/steam/apps/21000/page_bg_generated_v6b.jpg?t=1573509038"),
 *
 *          @OA\Property(
 *              property="screenshots",
 *              type="array",
 *              @OA\Items(
 *                   @OA\Property(property="id", type="int", example="0"),
 *                   @OA\Property(property="path_thumbnail", type="string", example="https://cdn.akamai.steamstatic.com/steam/apps/21000/0000005467.600x338.jpg?t=1573509038"),
 *                   @OA\Property(property="path_full", type="string", example="https://cdn.akamai.steamstatic.com/steam/apps/21000/0000005467.1920x1080.jpg?t=1573509038"),
 *                  ),
 *              ),
 *          ),
 *      ),
 * ))
 */
class GameDetailsController extends Controller
{
    public function __invoke(ShowGameService $service, int $appId): JsonResponse
    {
        $gameDetails = $service->get($appId);

        return new JsonResponse($gameDetails);
    }
}
