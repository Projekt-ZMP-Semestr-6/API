<?php

declare(strict_types = 1);

namespace App\Http\Controllers\User;

use App\Exceptions\User\UserNotFoundException;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * @OA\Get(
 * path="/api/user",
 * summary="Get user info",
 * description="Get info about currently authenticated user",
 * operationId="userInfo",
 * tags={"User"},
 * security={{"sanctum": {}}},
 * @OA\Response(
 *      response=200,
 *      description="OK",
 *      @OA\JsonContent(
 *          ref="#/components/schemas/User",
 *      ),
 * ),
 * @OA\Response(
 *      response=422,
 *      description="Unprocessable Content",
 *      @OA\JsonContent(
 *          @OA\Schema(ref="#/components/schemas/verifyEmail")
 *      ),
 * ))
 */
class UserController extends Controller
{
    public function __invoke(Request $request): JsonResponse
    {
        $user = $request->user('sanctum')
                ?? throw new UserNotFoundException;

        $user = new UserResource($user);

        return new JsonResponse($user);
    }
}
