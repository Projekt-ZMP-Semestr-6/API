<?php

declare(strict_types = 1);

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\DeleteAccountRequest;
use App\Services\User\AccountDeleter;
use Illuminate\Http\JsonResponse;

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
    public function __invoke(DeleteAccountRequest $request, AccountDeleter $deleter): JsonResponse
    {
        $user = $request->user('sanctum');

        $deleter->delete($user);

        return new JsonResponse('Account deleted!');
    }
}
