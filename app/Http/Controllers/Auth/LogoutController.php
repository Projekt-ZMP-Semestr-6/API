<?php

declare(strict_types = 1);

namespace App\Http\Controllers\Auth;

use App\Exceptions\Auth\LogoutException;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Throwable;

/**
 * @OA\Get(
 * path="/api/auth/logout",
 * summary="Log out",
 * description="Log out user",
 * operationId="logout",
 * tags={"Auth"},
 * security={{"sanctum": {}}},
 * @OA\Response(response=200, description="OK")
 * )
 */
class LogoutController extends Controller
{
    public function __invoke(Request $request): JsonResponse
    {
        $token = $request->user()->currentAccessToken();

        try {
            $token->delete();
        } catch(Throwable) {
            throw new LogoutException();
        }

        return new JsonResponse();
    }
}
