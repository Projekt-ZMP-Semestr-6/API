<?php

declare(strict_types = 1);

namespace App\Services\Auth;

use App\Exceptions\Auth\UserNotLoggedOutException;
use Laravel\Sanctum\PersonalAccessToken;
use Throwable;

class UserLogout
{
    public function logout(PersonalAccessToken $token): void
    {
        try {
            $token->delete();
        } catch(Throwable) {
            throw new UserNotLoggedOutException();
        }
    }
}
