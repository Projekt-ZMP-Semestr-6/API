<?php

declare(strict_types = 1);

namespace App\Http\Requests\Auth;

use App\Models\User;
use Illuminate\Foundation\Auth\EmailVerificationRequest as OriginalEmailVerificationRequest;

class EmailVerificationRequest extends OriginalEmailVerificationRequest
{
    public function authorize(): bool
    {
        if (! auth()->check()) {
            $this->resolveUser((string) $this->route('id'));
        }

        if (! hash_equals((string) $this->route('id'),
                          (string) $this->user()->getKey())) {
            return false;
        }

        if (! hash_equals((string) $this->route('hash'),
                          sha1($this->user()->getEmailForVerification()))) {
            return false;
        }

        return true;
    }

    protected function resolveUser(string $id): void
    {
        $this->setUserResolver(function () use ($id) {
            return User::findOrFail($id);
        });
    }
}
