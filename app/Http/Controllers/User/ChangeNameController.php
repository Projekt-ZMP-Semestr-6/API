<?php

namespace App\Http\Controllers\User;

use App\Exceptions\User\ChangeNameException;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\ChangeNameRequest;
use Illuminate\Http\JsonResponse;
use Throwable;

/**
 * @OA\Post(
 * path="/api/user/name",
 * summary="Change name",
 * description="Change user's name",
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
 * ),
 * )
 */
class ChangeNameController extends Controller
{
    public function __invoke(ChangeNameRequest $request): JsonResponse
    {
        $newName = $request->validated('name');
        $user = $request->user('sanctum');

        try {
            $user->name = $newName;
            $user->save();
        } catch (Throwable) {
            throw new ChangeNameException;
        }

        return new JsonResponse('Name updated!');
    }
}
