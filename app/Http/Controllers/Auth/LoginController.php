<?php

declare(strict_types = 1);

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Models\User;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    public function __invoke(LoginRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $deviceName = $validated['device_name'];

        $user = User::where('email', $validated['email'])->first();

        if(!$user || !Hash::check($validated['password'], $user->password)) {
            throw new HttpResponseException(new JsonResponse('Incorrect credentials.', 422));
        }

        $token = $user->tokens->where('name', $deviceName)->first();
        $token?->delete();

        $token = $user->createToken($deviceName)->plainTextToken;

        return new JsonResponse(['Bearer' => $token]);
    }
}
