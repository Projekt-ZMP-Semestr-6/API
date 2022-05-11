<?php

declare(strict_types = 1);

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\UpdateEmailRequest;
use App\Services\User\EmailUpdater;
use Illuminate\Http\JsonResponse;

/**
 * @OA\Put(
 * path="/api/user/email",
 * summary="Update email",
 * description="Update user's email",
 * operationId="userEmail",
 * tags={"User"},
 * security={{"sanctum": {}}},
 * @OA\RequestBody(
 *      description="Pass new email and password to confirm the change",
 *      @OA\JsonContent(
 *          required={"password", "email"},
 *          @OA\Property(
 *              property="password",
 *              type="password",
 *              example="password123",
 *          ),
 *          @OA\Property(
 *              property="email",
 *              type="email",
 *              example="new@email.com"
 *          ),
 *      ),
 * ),
 * @OA\Response(
 *      response=200,
 *      description="OK",
 *      @OA\JsonContent(
 *          type="string",
 *          example="Email updated!",
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
 *      ),
 * ))
 */
class UpdateEmailController extends Controller
{
    public function __invoke(UpdateEmailRequest $request, EmailUpdater $updater): JsonResponse
    {
        $user = $request->user('sanctum');
        $newEmail = $request->validated('email');

        $updater->update($user, $newEmail);

        return new JsonResponse('Email updated!');
    }
}
