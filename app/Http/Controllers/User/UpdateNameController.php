<?php

declare(strict_types = 1);

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\UpdateNameRequest;
use App\Services\User\NameUpdater;
use Illuminate\Http\JsonResponse;

/**
 * @OA\Put(
 * path="/api/user/name",
 * summary="Update name",
 * description="Update user's name",
 * operationId="userName",
 * tags={"User"},
 * security={{"sanctum": {}}},
 * @OA\RequestBody(
 *      description="Pass name to set",
 *      @OA\JsonContent(
 *          required={"name"},
 *          @OA\Property(
 *              property="name",
 *              type="string",
 *              example="Bob4",
 *          ),
 *      ),
 * ),
 * @OA\Response(
 *      response=200,
 *      description="OK",
 *      @OA\JsonContent(
 *          type="string",
 *          example="Name updated!",
 *      ),
 * ),
 * @OA\Response(
 *      response=422,
 *      description="Unprocessable Content",
 *      @OA\JsonContent(
 *          @OA\Property(
 *              property="name",
 *              type="array",
 *              @OA\Items(
 *                  type="string",
 *                  example="problem_message",
 *              ),
 *          ),
 *      ),
 * ))
 */
class UpdateNameController extends Controller
{
    public function __invoke(UpdateNameRequest $request, NameUpdater $updater): JsonResponse
    {
        $user = $request->user('sanctum');
        $newName = $request->validated('name');

        $updater->update($user, $newName);

        return new JsonResponse('Name updated!');
    }
}
