<?php

namespace App\Http\Controllers;

use App\Exceptions\UserNotFoundException;
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
 *          type="string",
 *          example="First, you need to verify email address."
 *      ),
 * ))
 */
class UserController extends Controller
{
    public function __invoke(Request $request): JsonResponse
    {
        $user = $request->user('sanctum');

        $user ?? throw new UserNotFoundException;

        return new JsonResponse($user);
    }
}
