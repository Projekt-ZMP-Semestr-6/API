<?php

declare(strict_types = 1);

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\ForgotPasswordRequest;
use App\Http\Requests\ResetPasswordRequest;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;

class ResetPasswordController extends Controller
{
    public function sendNotification(ForgotPasswordRequest $request): JsonResponse
    {
        $status = Password::sendResetLink(
            $request->safe()->only('email')
        );

        switch($status) {
            case Password::RESET_LINK_SENT:
                return new JsonResponse(['status' => __($status)]);
                break;

            case Password::RESET_THROTTLED:
                return new JsonResponse(['status' => __($status)], 429);
                break;

            default:
                return new JsonResponse(['email' => __($status)], 422);
        }
    }

    public function resetPassword(ResetPasswordRequest $request): JsonResponse
    {
        $validated = $request->validated();

        $callback = function($user, $password) {
            $password = ['password' => Hash::make($password)];
            $user->forceFill($password)->setRememberToken(Str::random(60));
            $user->save();

            event(new PasswordReset($user));
        };

        $status = Password::reset($validated, $callback);

        return $status === Password::PASSWORD_RESET
                    ?new JsonResponse(['status' => __($status)])
                    :new JsonResponse(['status' => __($status)], 422);
    }
}
