<?php

declare(strict_types = 1);

namespace App\Services\User;

use App\Exceptions\User\NameNotUpdatedException;
use App\Models\User;
use Throwable;

class NameUpdater
{
    public function update(User $user, string $newName): void
    {
        try {
            $user->name = $newName;
            $user->save();
        } catch (Throwable) {
            throw new NameNotUpdatedException;
        }
    }
}
