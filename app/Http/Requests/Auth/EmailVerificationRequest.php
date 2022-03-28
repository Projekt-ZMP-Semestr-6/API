<?php

declare(strict_types = 1);

namespace App\Http\Requests\Auth;

use App\Models\User;
use Closure;
use Illuminate\Foundation\Auth\EmailVerificationRequest as OriginalEmailVerificationRequest;

class EmailVerificationRequest extends OriginalEmailVerificationRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
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

    protected function resolveUser(string $id)
    {
        $this->setUserResolver(function () use ($id) {
            return User::findOrFail($id);
        });
    }
}
