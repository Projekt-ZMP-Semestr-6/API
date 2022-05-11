<?php

declare(strict_types = 1);

namespace App\Services\User;

use App\Exceptions\User\PasswordNotUpdatedException;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Throwable;

class PasswordUpdater
{
    public function update(User $user, string $newPassword): void
    {
        try {
            $user->password = Hash::make($newPassword);
            $user->save();
        } catch (Throwable) {
            throw new PasswordNotUpdatedException;
        }
    }
}
