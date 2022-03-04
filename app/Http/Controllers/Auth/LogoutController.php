<?php

declare(strict_types = 1);

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Laravel\Sanctum\PersonalAccessToken;

class LogoutController extends Controller
{
    public function __invoke(Request $request): JsonResponse
    {
        $token = $request->bearerToken();
        PersonalAccessToken::findToken($token)->delete();

        return new JsonResponse(null, 200);
    }
}
