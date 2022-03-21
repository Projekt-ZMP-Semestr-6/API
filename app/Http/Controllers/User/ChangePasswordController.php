<?php

namespace App\Http\Controllers\User;

use App\Exceptions\User\ChangePasswordException;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\ChangePasswordRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Throwable;

/**
 * @OA\Post(
 * path="/api/user/password",
 * summary="Change password",
 * description="Change user's password",
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
 * ),
 * )
 */
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
