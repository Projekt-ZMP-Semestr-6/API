<?php

declare(strict_types = 1);

namespace App\Http\Controllers\User;

use App\Exceptions\User\PasswordNotUpdatedException;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\UpdatePasswordRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Throwable;

/**
 * @OA\Put(
 * path="/api/user/password",
 * summary="Update password",
 * description="Update user's password",
 * operationId="userPassword",
 * tags={"User"},
 * security={{"sanctum": {}}},
 * @OA\RequestBody(
 *      description="Pass old and new password",
 *      @OA\JsonContent(
 *          required={"old_password", "new_password", "new_password_confirmation"},
 *          @OA\Property(
 *              property="old_password",
 *              type="password",
 *              example="password123"
 *          ),
 *          @OA\Property(
 *              property="new_password",
 *              type="password",
 *              example="password12345"
 *          ),
 *          @OA\Property(
 *              property="new_password_confirmation",
 *              type="password",
 *              example="password12345"
 *          ),
 *      ),
 * ),
 * @OA\Response(
 *      response=200,
 *      description="OK",
 *      @OA\JsonContent(
 *          type="string",
 *          example="Password updated!"
 *      ),
 * ),
 * @OA\Response(
 *      response=422,
 *      description="Unprocessable Content",
 *      @OA\JsonContent(
 *          @OA\Property(
 *              property="attribiute_name",
 *              type="array",
 *              @OA\Items(
 *                  type="string",
 *                  example="problem_message",
 *              ),
 *          ),
 *          @OA\Property(
 *              property="other_attribiute_name",
 *              type="array",
 *              @OA\Items(
 *                  type="string",
 *                  example="problem_message",
 *              ),
 *          ),
 *      ),
 * ))
 */
class UpdatePasswordController extends Controller
{
    public function __invoke(UpdatePasswordRequest $request): JsonResponse
    {
        $newPassword = $request->validated('new_password');
        $user = $request->user('sanctum');

        try {
            $user->password = Hash::make($newPassword);
            $user->save();
        } catch (Throwable) {
            throw new PasswordNotUpdatedException;
        }

        return new JsonResponse('Password updated!');
    }
}
