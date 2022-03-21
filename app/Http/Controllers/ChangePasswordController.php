<?php

namespace App\Http\Controllers;

use App\Exceptions\ChangePasswordException;
use App\Http\Requests\ChangePasswordRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Throwable;

class ChangePasswordController extends Controller
{
    public function __invoke(ChangePasswordRequest $request): JsonResponse
    {
        $newPassword = $request->validated('new_password');
        $user = $request->user();

        try {
            $user->password = Hash::make($newPassword);
            $user->save();
        } catch (Throwable) {
            throw new ChangePasswordException;
        }

        return new JsonResponse('Password updated!');
    }
}
