<?php

declare(strict_types = 1);

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\ForgotPasswordRequest;
use App\Http\Requests\Auth\ResetPasswordRequest;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;

/**
 * @OA\Post(
 * path="/api/forgot-password/send",
 * summary="Request notification",
 * description="Request reset password notification",
 * operationId="sendResetPasswordNotification",
 * tags={"Forgot Password"},
 * @OA\RequestBody(
 *      @OA\JsonContent(
 *          required={"email"},
 *          @OA\Property(property="email", type="email", example="test@test.com")
 *      ),
 * ),
 * @OA\Response(response=200, description="OK", @OA\Property(property="status", type="string", example="We have emailed your password reset link!")),
 * @OA\Response(response=429, description="Too Many Requests", @OA\Property(property="status", type="string", example="Please wait before retrying.")),
 * @OA\Response(
 *      response=422,
 *      description="Unprocessable Content",
 *      @OA\Property(
 *          property="email",
 *          type="array",
 *          @OA\Items(
 *              type="string",
 *              example="problem_message",
 *          ),
 *      ),
 * )
 * )
 *
 * @OA\Post(
 * path="/api/forgot-password/reset",
 * summary="Reset password",
 * description="Reset password",
 * operationId="ResetPassword",
 * tags={"Forgot Password"},
 * @OA\RequestBody(
 *      @OA\JsonContent(
 *          required={"token", "email", "password", "password_confirmation"},
 *          @OA\Property(property="token", type="string", example="aa2aa9436157bad1f287119f85ac52127dfc40e5d9a58dd3886ac258a3e259b0"),
 *          @OA\Property(property="email", type="email", example="test@test.com"),
 *          @OA\Property(property="password", type="password", example="password123"),
 *          @OA\Property(property="password_confirmation", type="password", example="password123"),
 *      ),
 * ),
 * @OA\Response(response=200, description="OK", @OA\Property(property="status", type="string", example="Your password has been reset!")),
 * @OA\Response(
 *      response=422,
 *      description="Unprocessable Content",
 *      @OA\Property(
 *          property="attribiute_name",
 *          type="array",
 *          @OA\Items(
 *              type="string",
 *              example="problem_message",
 *          ),
 *      ),
 * ),
 * )
 */
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
