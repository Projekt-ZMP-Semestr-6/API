<?php

declare(strict_types = 1);

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use App\Services\Auth\UserRegistrar;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;

/**
 * @OA\Post(
 * path="/api/auth/register",
 * summary="Register",
 * description="Register new account",
 * operationId="register",
 * tags={"Auth"},
 * @OA\RequestBody(
 *      required=true,
 *      description="Pass user credentials",
 *      @OA\JsonContent(
 *          required={"email", "name", "password", "password_confirmation"},
 *          @OA\Property(
 *              property="email",
 *              type="string",
 *              format="email",
 *              example="test@test.com"
 *          ),
 *          @OA\Property(
 *              property="name",
 *              type="string",
 *              example="Greg"
 *          ),
 *          @OA\Property(
 *              property="password",
 *              type="string",
 *              format="password",
 *              example="password123"
 *          ),
 *          @OA\Property(
 *              property="password_confirmation",
 *              type="string",
 *              format="password",
 *              example="password123"
 *          ),
 *      ),
 * ),
 * @OA\Response(
 *      response=201,
 *      description="Created",
 *      @OA\JsonContent(
 *          ref="#/components/schemas/User",
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
class RegisterController extends Controller
{
    public function __invoke(RegisterRequest $request, UserRegistrar $registrar): JsonResponse
    {
        $validated = $request->validated();
        $validated['password'] = Hash::make($validated['password']);

        $user = $registrar->register($validated);

        return new JsonResponse($user, 201);
    }
}
