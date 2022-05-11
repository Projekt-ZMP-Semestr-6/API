<?php

declare(strict_types = 1);

namespace App\Services\User;

use App\Exceptions\User\EmailNotUpdatedException;
use App\Models\User;
use Throwable;

class EmailUpdater
{
    public function update(User $user, string $newEmail): void
    {
        try {
            $user->email = $newEmail;
            $user->save();
        } catch (Throwable) {
            throw new EmailNotUpdatedException;
        }
    }
}
