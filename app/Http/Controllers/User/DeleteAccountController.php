<?php

namespace App\Http\Controllers\User;

use App\Exceptions\UserNotDeletedException;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\DeleteAccountRequest;
use Illuminate\Http\JsonResponse;
use Throwable;

class DeleteAccountController extends Controller
{
    public function __invoke(DeleteAccountRequest $request): JsonResponse
    {
        try {
            $user = $request->user();
            $user->tokens()->delete();
            $user->delete();
        } catch (Throwable) {
            throw new UserNotDeletedException;
        }

        return new JsonResponse('Account deleted!');
    }
}
