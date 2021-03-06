<?php

declare(strict_types = 1);

namespace App\Http\Controllers\Auth;

use App\Exceptions\Auth\EmailNotVerifiedException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\EmailVerificationRequest;
use App\Http\Requests\Auth\ResendEmailVerificationNotificationRequest;
use Illuminate\Http\JsonResponse;

/**
 * @OA\Post(
 * path="/api/auth/email/verification-notification",
 * summary="Resend notification",
 * description="Resend email verification notification",
 * operationId="resendEmailVerification",
 * tags={"Email verification"},
 * @OA\RequestBody(
 *      description="Pass the email to which the notification will be send",
 *      @OA\JsonContent(
 *          @OA\Property(
 *              property="email",
 *              type="email",
 *              example="test@test.com",
 *          ),
 *      ),
 * ),
 * @OA\Response(
 *      response=200,
 *      description="OK",
 *      @OA\JsonContent(
 *          type="string",
 *          example="Mail sent!",
 *      ),
 * ),
 * @OA\Response(
 *      response=429,
 *      description="Too Many Requests",
 * )),
 *
 * @OA\Get(
 * path="/api/auth/email/verify/{id}/{hash}",
 * summary="Verify email",
 * description="Verify user's email",
 * operationId="verifyEmail",
 * tags={"Email verification"},
 * @OA\Parameter(
 *      name="id",
 *      in="path",
 *      description="User's id",
 *      required=true,
 *      example="95e36453-b0e0-4827-8e05-3bf13981dc28",
 * ),
 * @OA\Parameter(
 *      name="hash",
 *      in="path",
 *      description="Hash from server",
 *      required=true,
 *      example="552cb23b88d656134881275f5925d5c2e252ca78",
 * ),
 * @OA\Parameter(
 *      name="expires",
 *      in="query",
 *      description="Time until link expire",
 *      required=true,
 *      example="1648044292",
 * ),
 * @OA\Parameter(
 *      name="signature",
 *      in="query",
 *      description="Signature from server",
 *      required=true,
 *      example="1ce16e79c7392251ea3969fd1774a674efb5173efc6c4ab4d90446d58d515869",
 * ),
 * @OA\Response(
 *      response=200,
 *      description="OK",
 *      @OA\JsonContent(
 *          type="string",
 *          example="Email verified!",
 *      ),
 * ),
 * @OA\Response(
 *      response=403,
 *      description="Forbidden",
 * ))
 */
class EmailVerificationController extends Controller
{

    public function notice(): void
    {
        throw new EmailNotVerifiedException;
    }

    public function verify(EmailVerificationRequest $request): JsonResponse
    {
        $request->fulfill();

        return new JsonResponse('Email verified!');
    }

    public function resendMail(ResendEmailVerificationNotificationRequest $request): JsonResponse
    {
        $request->user()->sendEmailVerificationNotification();

        return new JsonResponse('Mail sent!');
    }
}
