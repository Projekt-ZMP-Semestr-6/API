<?php

declare(strict_types = 1);

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * @OA\Post(
 * path="/api/auth/email/verification-notification",
 * summary="Resend notification",
 * description="Resend email verification notification",
 * operationId="resendEmailVerification",
 * tags={"Email verification"},
 * security={{"sanctum": {}}},
 * @OA\Response(response=200, description="OK", @OA\JsonContent(type="string", example="Mail sent!")),
 * @OA\Response(response=429, description="Too Many Requests")),
 * )
 */
class EmailVerificationController extends Controller
{

    public function notice(): JsonResponse
    {
        return new JsonResponse('First, you need to verify email address.', 422);
    }

    public function verify(EmailVerificationRequest $request): void
    {
        $request->fulfill();

        redirect(env('FRONTEND_URL'));
    }

    public function resendMail(Request $request): JsonResponse
    {
        $request->user()->sendEmailVerificationNotification();

        return new JsonResponse('Mail sent!');
    }
}
