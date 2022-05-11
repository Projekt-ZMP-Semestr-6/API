<?php

declare(strict_types = 1);

namespace App\Services\Auth;

use App\Exceptions\Auth\UserNotLoggedInException;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserLogin
{
    public function login(array $validated): string
    {
        $user = User::whereEmail($validated['email'])->first();

        if(!$user || !Hash::check($validated['password'], $user->password)) {
            throw new UserNotLoggedInException();
        }

        $user->tokens->where('name', $validated['device_name'])->first()?->delete();
        $token = $user->createToken($validated['device_name'])->plainTextToken;

        return $token;
    }
}
