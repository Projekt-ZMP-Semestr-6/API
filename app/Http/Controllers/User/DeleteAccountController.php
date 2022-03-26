<?php

declare(strict_types = 1);

namespace App\Http\Controllers\User;

use App\Exceptions\User\UserNotDeletedException;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\DeleteAccountRequest;
use Illuminate\Http\JsonResponse;
use Throwable;

/**
 * @OA\Delete(
 * path="/api/user/delete",
 * summary="Delete account",
 * description="Delete currently authenticated user",
 * operationId="userDelete",
 * tags={"User"},
 * security={{"sanctum": {}}},
 * @OA\RequestBody(
 *      description="Pass password to confirm deletion",
 *      @OA\JsonContent(
 *          required={"password"},
 *          @OA\Property(
 *              property="password",
 *              type="password",
 *              example="password123",
 *          ),
 *      ),
 * ),
 * @OA\Response(
 *      response=200,
 *      description="OK",
 *      @OA\JsonContent(
 *          type="string",
 *          example="Account deleted!",
 *      ),
 * ),
 * @OA\Response(
 *      response=422,
 *      description="Unprocessable Content",
 *      @OA\JsonContent(
 *          @OA\Property(
 *              property="password",
 *              type="array",
 *              @OA\Items(
 *                  type="string",
 *                  example="problem_message",
 *              ),
 *          ),
 *      ),
 * ))
 */
class DeleteAccountController extends Controller
{
    public function __invoke(DeleteAccountRequest $request): JsonResponse
    {
        try {
            $request->user('sanctum')->delete();
        } catch (Throwable) {
            throw new UserNotDeletedException;
        }

        return new JsonResponse('Account deleted!');
    }
}
