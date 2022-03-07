<?php

declare(strict_types = 1);

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class EmailVerificationController extends Controller
{
    public function notice(): JsonResponse
    {
        return new JsonResponse('First, you need to verify email address.');
    }

    public function verify(EmailVerificationRequest $request): void
    {
        $request->fulfill();

        redirect(env('FRONTEND_URL'));
    }

    public function resendMail(Request $request): JsonResponse
    {
        $request->user()->sendEmailVerificationNotification();

        return new JsonResponse('Mail send!');
    }
}
