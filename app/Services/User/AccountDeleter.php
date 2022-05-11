<?php

declare(strict_types = 1);

namespace App\Services\User;

use App\Exceptions\User\UserNotDeletedException;
use App\Models\User;
use Throwable;

class AccountDeleter
{
    public function delete(User $user): void
    {
        try {
            $user->delete();
        } catch (Throwable) {
            throw new UserNotDeletedException;
        }
    }
}
