<?php

declare(strict_types = 1);

namespace App\Http\Controllers\Auth;

use App\Exceptions\LogoutException;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Laravel\Sanctum\PersonalAccessToken;
use Throwable;

class LogoutController extends Controller
{
    public function __invoke(Request $request): JsonResponse
    {
        $token = $request->bearerToken();

        try {
            PersonalAccessToken::findToken($token)->delete();
        } catch(Throwable) {
            throw new LogoutException();
        }

        return new JsonResponse(null, 200);
    }
}
