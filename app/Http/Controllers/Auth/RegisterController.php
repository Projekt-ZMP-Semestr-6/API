<?php

declare(strict_types = 1);

namespace App\Http\Controllers\Auth;

use App\Exceptions\UserCreationException;
use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Throwable;

class RegisterController extends Controller
{
    public function __invoke(RegisterRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $validated['password'] = Hash::make($validated['password']);

        try {
            $user = User::create($validated);
        } catch(Throwable) {
            throw new UserCreationException();
        }

        event(new Registered($user));

        return new JsonResponse($user, 201);
    }
}
